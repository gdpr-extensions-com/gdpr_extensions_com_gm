<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComGm\Controller;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "gdpr-extensions.com_gdpr_map" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023
 */

/**
 * MapController
 */
class MapController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * mapRepository
     *
     * @var \GdprExtensionsCom\GdprExtensionsComGm\Domain\Repository\MapRepository
     */
    protected $mapRepository = null;

    /**
     * ContentObject
     *
     * @var ContentObject
     */
    protected $contentObject;

     /**
     * Action initialize
     */
    protected function initializeAction()
    {
        $this->contentObject = $this->configurationManager->getContentObject(); // intialize the content object
    }

    /**
     * @param \GdprExtensionsCom\GdprExtensionsComGm\Domain\Repository\MapRepository $mapRepository
     */
    public function injectMapRepository(\GdprExtensionsCom\GdprExtensionsComGm\Domain\Repository\MapRepository $mapRepository)
    {
        $this->mapRepository = $mapRepository;
    }

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {   

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $multilocationQB = $connectionPool->getQueryBuilderForTable('multilocations');
        $mapQB = $connectionPool->getQueryBuilderForTable(' tx_gdprextensionscomgm_domain_model_map');

        $rootPid = $this->findRootPid($GLOBALS['TSFE']->page['uid']);
                
        $dashboard_api_key = '';
        
        if($this->contentObject->data && !empty($this->contentObject->data['gdpr_map_business_locations'])){

            $selectedLocation =$this->contentObject->data['gdpr_map_business_locations'];
            
                $locationResult = $multilocationQB->select('dashboard_api_key')
                    ->from('multilocations')
                    ->where(
                        $multilocationQB->expr()
                            ->eq('location_page_id', $rootPid)
                    )
                    ->executeQuery();
                 $dashboard_api_key=$locationResult->fetchAssociative()['dashboard_api_key'] ?? '';
                 
        }
       
        $selectedLocationArray = explode(',', $selectedLocation);

        if ($dashboard_api_key != '') {
            $queryBuilder = $mapQB;  // Alias for better readability
            $query = $queryBuilder->select('*')
                ->from('tx_gdprextensionscomgm_domain_model_map')
                ->where(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('dashboard_api_key', $queryBuilder->createNamedParameter($dashboard_api_key)),
                        $queryBuilder->expr()->eq('root_pid', $queryBuilder->createNamedParameter($rootPid)),
                        $queryBuilder->expr()->in('uid', $queryBuilder->createNamedParameter($selectedLocationArray, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY))
                    )
                );

            $map = $query->executeQuery()->fetchAllAssociative();
        }

     if($map){
        $this->view->assign('map', $map);
     }

        return $this->htmlResponse();
    }

    protected function findRootPid(int $pageUid): int
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('pages');

        $row = $queryBuilder
            ->select('uid', 'pid', 'is_siteroot')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($pageUid, \PDO::PARAM_INT))
            )
            ->execute()
            ->fetch();
        if ($row && $row['is_siteroot']) {
            return (int)$row['uid'];
        } elseif ($row) {
            return $this->findRootPid((int)$row['pid']);
        }

        return 0;
    }
}

<?php

namespace GdprExtensionsCom\GdprExtensionsComGm\Utility;

use GuzzleHttp\Client;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class SyncMaps
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function run(ConnectionPool $connectionPool, Logger $logManager)
    {
        
        $multilocationQB = $connectionPool->getQueryBuilderForTable('multilocations');
        $sysTempQB = $connectionPool->getQueryBuilderForTable('sys_template');
        $mapQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomgm_domain_model_map');

        $apiKeys = [];
        $BaseUris = [];

        $multilocationQBResult = $multilocationQB
            ->select('*')
            ->from('multilocations')
            ->executeQuery()
            ->fetchAllAssociative();
        
        foreach ($multilocationQBResult as $location) {

            $apiKey = $location['dashboard_api_key'] ?? null;
            $SiteConfiguration = $sysTempQB->select('constants')
            ->from('sys_template')
            ->where(
                $sysTempQB->expr()->eq('pid', $sysTempQB->createNamedParameter($location['pages'])),
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();
            $sysTempQB->resetQueryParts();

            $constantsArray = $this->extractSecretKey($SiteConfiguration['constants']);
            $BaseURL = NULL;
            
            if ($apiKey) {
                if (!isset($apiKeys[$location['pages']])) {
                    $apiKeys[$location['pages']] = [];
                }
                $apiKeys[$location['pages']]  = array_merge($apiKeys[$location['pages']], [$apiKey]);
                $BaseUris[$location['pages']] = $BaseURL;
            }
        }
        $client = new Client();
        foreach ($apiKeys as $rootId => $apiKey) {
            foreach ($apiKey as $multiLocApiKey){
            try{
            $requestUrl = (is_null($BaseUris[$rootId]) ? 'https://dashboard.gdpr-extensions.com/': $BaseUris[$rootId]).'review/api/' . $multiLocApiKey . '/maps-list.json';
            $response = $client->get($requestUrl);
            $maps = json_decode($response->getBody()->getContents(), true);
            print_r([$requestUrl,$maps]);
            if(is_null($BaseUris[$rootId]))
            {
                $BaseUris[$rootId] = 'https://dashboard.gdpr-extensions.com/';
            }

            if(!$maps)
            {
                $mapQB->resetQueryParts();
                $delMap=$mapQB->select('*')
                    ->from('tx_gdprextensionscomgm_domain_model_map')
                    ->where(
                        $mapQB->expr()->eq('dashboard_api_key', $mapQB->createNamedParameter($multiLocApiKey)),
                        $mapQB->expr()->eq('root_pid', $mapQB->createNamedParameter($rootId))
                    )
                    ->executeQuery()
                    ->fetchAssociative();
                if($delMap)
                {   $mapQB->resetQueryParts();
                    $this->deleteFileById($delMap['root_pid'].$delMap['remote_uid']);
                    // Delete map if removed from dashboard
                    $mapQB
                    ->delete('tx_gdprextensionscomgm_domain_model_map')
                    ->where(
                        $mapQB->expr()->eq('dashboard_api_key', $mapQB->createNamedParameter($multiLocApiKey)),
                        $mapQB->expr()->eq('root_pid', $mapQB->createNamedParameter($rootId))
                    )
                    ->executeStatement();

                    
                }
               
                

            }
            foreach ($maps as $map) {
                $this->syncMap($map, $connectionPool, $BaseUris[$rootId],$rootId,$multiLocApiKey);
            }
        }
        catch(\Exception $e){
            
        }
        }
    }
    
    }

    protected function extractSecretKey($constantsString)
    {
        $configLines = explode("\n", $constantsString);
        $configArray = [];

        foreach ($configLines as $line) {
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $configArray[trim($key)] = trim($value);
            }
        }
        return $configArray;
    }

    /**
     *
     * @param array $map
     * @param ConnectionPool $connectionPool
     * @throws \Doctrine\DBAL\Exception
     */
    private function syncMap(array $map, ConnectionPool $connectionPool, string $baseUri,string $rootId,string $apiKey)
    {
        $connection = $connectionPool->getConnectionForTable('tx_gdprextensionscomgm_domain_model_map');
        $existingMap = $this->findMap($map['uid'],$rootId, $connection);

        $iconPath = $map['iconPath'] == 'EXT:go_sitepackage/Resources/Public/Images/marker.png' ?
            'EXT:gdpr_extensions_com_gm/Resources/Public/images/marker.png' :
            $this->downloadAndSave($map['iconPath'], $map['uid'], $baseUri, 'icon.png');

        $mapData = [
            'title' => $map['title'],
            'icon_path' => $iconPath,
            'map_path' => $this->downloadAndSave($map['mapPath'],($rootId.$map['uid']), $baseUri, 'map.png'),
            'locations' => count($map['locations']),
            'remote_uid' => $map['uid'],
            'root_pid' => $rootId,
            'lon' => $map['lon'],
            'lat' => $map['lat'],
            'zoom' => $map['zoom'],
            'dashboard_api_key'=>$apiKey
        ];

        if ($existingMap) {
            $connection->update(
                'tx_gdprextensionscomgm_domain_model_map',
                $mapData,
                ['uid' => $existingMap['uid']]
            );
        } else {
            $connection->insert('tx_gdprextensionscomgm_domain_model_map', $mapData);
        }
        // $mapUid = $existingMap['uid'] ?? $connection->lastInsertId();
        // foreach ($map['locations'] as $location) {
        //     $this->syncLocation($location, $connectionPool, $mapUid);
        // }
    }

    /**
     * @param string $remoteUid
     * @param Connection $connection
     * @return array|null
     * @throws \Doctrine\DBAL\Exception
     */
    private function findMap(string $remoteUid,string $rootId, Connection $connection): ?array
    {
        $qb = $connection->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('tx_gdprextensionscomgm_domain_model_map')
            ->where(
                $qb->expr()->and(
                    $qb->expr()->eq('remote_uid', $qb->createNamedParameter($remoteUid)),
                    $qb->expr()->eq('root_pid', $qb->createNamedParameter($rootId))
                )
            )
            ->execute()
            ->fetchAssociative();

        return $result ?: null;
    }

    /**
     * @param array $location
     * @param ConnectionPool $connectionPool
     * @param int $mapUid
     * @throws \Doctrine\DBAL\Exception
     */
    private function syncLocation(array $location, ConnectionPool $connectionPool, int $mapUid)
    {
        $connection = $connectionPool->getConnectionForTable('tx_gdprextensionscomgm_domain_model_maplocation');
        $existingLocation = $this->findLocation($location['uid'], $connection);
        $locationData = [
            'map' => $mapUid,
            'title' => $location['title'],
            'address' => $location['address'],
            'lat' => $location['lat'],
            'lon' => $location['lon'],
            'remote_uid' => $location['uid']
        ];

        if ($existingLocation) {
            $connection->update(
                'tx_gdprextensionscomgm_domain_model_maplocation',
                $locationData,
                ['uid' => $existingLocation['uid']]
            );
        } else {
            $connection->insert('tx_gdprextensionscomgm_domain_model_maplocation', $locationData);
        }
    }

    /**
     * @param string $remoteUid
     * @param Connection $connection
     * @return array|null
     * @throws \Doctrine\DBAL\Exception
     */
    private function findLocation(string $remoteUid, Connection $connection): ?array
    {
        $qb = $connection->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('tx_gdprextensionscomgm_domain_model_maplocation')
            ->where($qb->expr()->eq('remote_uid', $qb->createNamedParameter($remoteUid)))
            ->execute()
            ->fetchAssociative();

        return $result ?: null;
    }

    /**
     * @param $remoteIconPath
     * @param $mapId
     * @param $baseUri
     * @param $newFileName
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function downloadAndSave($remoteIconPath, $mapId, $baseUri, $newFileName)
    {
        $client = new Client();
        $response = $client->get($baseUri . $remoteIconPath);

        if ($response->getStatusCode() == 200) {
            $iconContent = $response->getBody()->getContents();
            $localDir = '../fileadmin/user_upload/maps/' . $mapId;
            $localDirFullPath = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/fileadmin/user_upload/maps/' . $mapId;
            if (!file_exists($localDir)) {
                mkdir($localDir, 0777, true);
            }
            $localPath = $localDirFullPath . '/' . $newFileName;
            file_put_contents($localPath, $iconContent);
            return 'fileadmin/user_upload/maps/' . $mapId . '/' . $newFileName;
        } else {
            return '';
        }
    }
    private function deleteFileById($id)
{
    $dirPath = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/fileadmin/user_upload/maps/' . $id;
    $filePath = $dirPath . '/map.png';
    
    // Check if the file exists
    if (file_exists($filePath)) {
        // Attempt to delete the file
        if (unlink($filePath)) {
            // Attempt to delete the directory
            if (rmdir($dirPath)) {
                return true; // File and directory deleted successfully
            } else {
                return false; // Failed to delete the directory
            }
        } else {
            return false; // Failed to delete the file
        }
    } else {
        return false; // File does not exist
    }
}
}

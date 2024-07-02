<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComGm\Commands;


use GdprExtensionsCom\GdprExtensionsComGm\Utility\SyncMaps;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class SyncMapsTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

    public function __construct()
    {
        parent::__construct();
    }

    public function execute()
    {
        $businessLogic = GeneralUtility::makeInstance(SyncMaps::class);
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);

        $businessLogic->run($connectionPool,$logger);
        return true;
    }
}

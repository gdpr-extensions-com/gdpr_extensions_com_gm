<?php

namespace GdprExtensionsCom\GdprExtensionsComGm\Utility;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;
class ProcessMultiLocation
{

    public function __construct()
    {
    }

    public function getLocationsforRoodPid(array &$params)
    {
        $helper = GeneralUtility::makeInstance(Helper::class);
        $rootpid = $helper->getRootPage($params['row']['pid']);
        
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $multiLocationQB = $connectionPool->getQueryBuilderForTable(
            'multilocations'
        );
       $apikeyQuery = $multiLocationQB->select('dashboard_api_key')
            ->from('multilocations')
            ->where(
                $multiLocationQB->expr()
                    ->eq('location_page_id', $multiLocationQB->createNamedParameter($rootpid))
            );

        $apikeyResult = $apikeyQuery->executeQuery();
        $apikey = $apikeyResult->fetchOne();

        $mapLocationQB = $connectionPool->getQueryBuilderForTable(
            'tx_gdprextensionscomgm_domain_model_map'
        );

        $locationResult = $mapLocationQB->select('*')
            ->from('tx_gdprextensionscomgm_domain_model_map')
            ->where(
                $mapLocationQB->expr()
                    ->eq('dashboard_api_key', $mapLocationQB->createNamedParameter($apikey)),
            )
            ->orderBy('title', 'DESC')
            ->executeQuery();

        while ($Location = $locationResult->fetchAssociative()) {

            if (strlen($Location['title']) < 1) {
                continue;
            }

            $params['items'][] = [$Location['title'], $Location['uid']];
        }
        return $params;
    }

     public function getErrorMssg(array &$params, &$data)
    {
        $arrayData = (array) $data;
        $installedExtensions = ExtensionManagementUtility::getLoadedExtensionListArray();
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_scheduler_task');

        // Determine TYPO3 version
                $typo3Version = VersionNumberUtility::getNumericTypo3Version();


        $taskSerializer = null;
        if ($typo3Version >= 12000000) { // TYPO3 v12 and above
            $taskSerializerClass = \TYPO3\CMS\Scheduler\Serializer\TaskSerializer::class;
            $taskSerializer = GeneralUtility::makeInstance($taskSerializerClass);
        }

        $queryBuilder->getRestrictions()->removeAll();
        $result = $queryBuilder
            ->select('*')
            ->from('tx_scheduler_task')
            ->where(
                $queryBuilder->expr()->eq('deleted', 0)
            )->executeQuery()->fetchAllAssociative();
        $helper = GeneralUtility::makeInstance(Helper::class);
        $rootpid =$helper->getRootPage($arrayData["\0*\0data"]['effectivePid']);

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $multiLocationQB = $connectionPool->getQueryBuilderForTable(
            'multilocations'
        );
       $multiLocationResult = $multiLocationQB->select('*')
            ->from('multilocations')
            ->where(
                $multiLocationQB->expr()
                    ->eq('location_page_id', $multiLocationQB->createNamedParameter($rootpid))
            )

             ->executeQuery()
             ->fetchAssociative();
        $mapLocationQB = $connectionPool->getQueryBuilderForTable(
            'tx_gdprextensionscomgm_domain_model_map'
        );
            if($multiLocationResult){

        $locationResult = $mapLocationQB->select('*')
            ->from('tx_gdprextensionscomgm_domain_model_map')
            ->where(
                $mapLocationQB->expr()
                    ->eq('dashboard_api_key', $mapLocationQB->createNamedParameter($multiLocationResult['dashboard_api_key'])),
            )
            ->orderBy('title', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative();
            }
         $messages = [];
        // Check if "gdpr_extensions_com_cm" is installed
        if (!in_array('gdpr_extensions_com_cm', $installedExtensions)) {
            array_push($messages,'Please install the GDPR Consent Manager Extension and connect your website to the GDPR dashboard. ');
        } 
        $UpdateOwnStatusTask = 0;
        $SyncMapTask = 0;
        
        if(!$multiLocationResult){
            array_push($messages,'Please install the GDPR Consent Manager Extension and connect your website to the GDPR dashboard. ');
        }
            if($multiLocationResult){
            foreach ($result as $schedulerTask){
                 if ($taskSerializer) {
                    $taskClassName = $taskSerializer->extractClassName($schedulerTask['serialized_task_object']);
                } else {
                    $taskObject = unserialize($schedulerTask['serialized_task_object']);
                    if ($taskObject instanceof AbstractTask) {
                        $taskClassName = get_class($taskObject);
                    } else {
                        continue;
                    }
                }
                // if($taskClassName == 'GdprExtensionsCom\GdprExtensionsComCm\Commands\UpdateOwnStatusTask'){
                //     $UpdateOwnStatusTask = 1;
                //     $updateOwnLastExecTime = $schedulerTask['lastexecution_time'];

                //     if($multiLocationResult['api_create_time'] > $updateOwnLastExecTime){
                //         // need to add flag here
                //         array_push($messages,'PLease run Update Website Status Scheduler!');
                //     }
                // }
                if($taskClassName == 'GdprExtensionsCom\GdprExtensionsComGm\Commands\SyncMapsTask'){
                    $SyncMapTask = 1;
                    $mapTaskLastExecTime = $schedulerTask['lastexecution_time'];
                    if($multiLocationResult['api_create_time'] > $mapTaskLastExecTime){
                        array_push($messages,'Please visit the schedulers page and run “Fetch Google Map”');

                    }else{
                        if (count($locationResult) <= 0){
                            array_push($messages, 'Map maybe disabled, please check on your GDPR dashboard.');
                        }
                    }
                }

            }

            // if(!$UpdateOwnStatusTask){
            // array_push($messages,'PLease add Update Website Status Scheduler!');
            // }
            // if(!$SyncMapTask){
            //     array_push($messages,'PLease add Fetch Google Map Scheduler!');
            // }
        }
        
        $string = '';
        $count = 1;
        foreach ($messages as $item) {
            $string .= "(".$count++.")"." - ".$item."   \n";
        }
        
        return $string;


    }
     public function errorMssg(array &$params, &$data)
    {
        
        $arrayData = (array) $data;
        $installedExtensions = ExtensionManagementUtility::getLoadedExtensionListArray();
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_scheduler_task');

        // Determine TYPO3 version
        $typo3Version = VersionNumberUtility::getNumericTypo3Version();


        $taskSerializer = null;
        if ($typo3Version >= 12000000) { // TYPO3 v12 and above
            $taskSerializerClass = \TYPO3\CMS\Scheduler\Serializer\TaskSerializer::class;
            $taskSerializer = GeneralUtility::makeInstance($taskSerializerClass);
        }

        $queryBuilder->getRestrictions()->removeAll();
        $result = $queryBuilder
            ->select('*')
            ->from('tx_scheduler_task')
            ->where(
                $queryBuilder->expr()->eq('deleted', 0)
            )->executeQuery()->fetchAllAssociative();
        $helper = GeneralUtility::makeInstance(Helper::class);
        $rootpid =$helper->getRootPage($params['record']['pid']);

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $multiLocationQB = $connectionPool->getQueryBuilderForTable(
            'multilocations'
        );
       $multiLocationResult = $multiLocationQB->select('*')
            ->from('multilocations')
            ->where(
                $multiLocationQB->expr()
                    ->eq('location_page_id', $multiLocationQB->createNamedParameter($rootpid))
            )

             ->executeQuery()
             ->fetchAssociative();
        $mapLocationQB = $connectionPool->getQueryBuilderForTable(
            'tx_gdprextensionscomgm_domain_model_map'
        );
            if($multiLocationResult){
        
            $locationResult = $mapLocationQB->select('*')
                ->from('tx_gdprextensionscomgm_domain_model_map')
                ->where(
                    $mapLocationQB->expr()
                        ->eq('dashboard_api_key', $mapLocationQB->createNamedParameter($multiLocationResult['dashboard_api_key'])),
                )
                ->orderBy('title', 'DESC')
                ->executeQuery()
                ->fetchAllAssociative();
            }
         $messages = [];
        // Check if "gdpr_extensions_com_cm" is installed
        if (!in_array('gdpr_extensions_com_cm', $installedExtensions)) {
            array_push($messages,'Gdpr extensions consent manager is not installed! ');
        } 
        $UpdateOwnStatusTask = 0;
        $SyncMapTask = 0;
        
        if(!$multiLocationResult){
            array_push($messages,'Valid Api Key Not Added!');
        }
            if($multiLocationResult){
            foreach ($result as $schedulerTask){
                 if ($taskSerializer) {
                    $taskClassName = $taskSerializer->extractClassName($schedulerTask['serialized_task_object']);
                } else {
                    $taskObject = unserialize($schedulerTask['serialized_task_object']);
                    if ($taskObject instanceof AbstractTask) {
                        $taskClassName = get_class($taskObject);
                    } else {
                        continue;
                    }
                }
                if($taskClassName == 'GdprExtensionsCom\GdprExtensionsComCm\Commands\UpdateOwnStatusTask'){
                    $UpdateOwnStatusTask = 1;
                    $updateOwnLastExecTime = $schedulerTask['lastexecution_time'];

                    if($multiLocationResult['api_create_time'] > $updateOwnLastExecTime){
                        // need to add flag here
                        array_push($messages,'PLease run Update Website Status Scheduler!');
                    }
                }
                if($taskClassName == 'GdprExtensionsCom\GdprExtensionsComGm\Commands\SyncMapsTask'){
                    $SyncMapTask = 1;
                    $mapTaskLastExecTime = $schedulerTask['lastexecution_time'];
                    if($multiLocationResult['api_create_time'] > $mapTaskLastExecTime){
                        array_push($messages,'PLease run Fetch Google Map Scheduler!');

                    }else{
                        if (count($locationResult) <= 0){
                            array_push($messages, 'No Map Found');
                        }
                    }
                }

            }

            if(!$UpdateOwnStatusTask){
            array_push($messages,'PLease add Update Website Status Scheduler!');
            }
            if(!$SyncMapTask){
                array_push($messages,'PLease add Fetch Google Map Scheduler!');
            }
        }
        
        $string = '';
        $count = 1;
        foreach ($messages as $item) {
            $string .= "(".$count++.")"." - ".$item."   \n";
        }
        

        return $string;


    }
     public function errorMssgLoc(array &$params, &$data)
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
         $helper = GeneralUtility::makeInstance(Helper::class);
        $rootpid =$helper->getRootPage($params['record']['pid']);
        $installedExtensions = ExtensionManagementUtility::getLoadedExtensionListArray();
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_scheduler_task');

        // Determine TYPO3 version
        $typo3Version = VersionNumberUtility::getNumericTypo3Version();


        $taskSerializer = null;
        if ($typo3Version >= 12000000) { // TYPO3 v12 and above
            $taskSerializerClass = \TYPO3\CMS\Scheduler\Serializer\TaskSerializer::class;
            $taskSerializer = GeneralUtility::makeInstance($taskSerializerClass);
        }

        $queryBuilder->getRestrictions()->removeAll();
        $result = $queryBuilder
            ->select('*')
            ->from('tx_scheduler_task')
            ->where(
                $queryBuilder->expr()->eq('deleted', 0)
            )->executeQuery()->fetchAllAssociative();

        $multiLocationQB = $connectionPool->getQueryBuilderForTable(
            'multilocations'
        );
       $multiLocationResult = $multiLocationQB->select('*')
            ->from('multilocations')
            ->where(
                $multiLocationQB->expr()
                    ->eq('location_page_id', $multiLocationQB->createNamedParameter($rootpid))
            )

             ->executeQuery()
             ->fetchAssociative();
        $mapLocationQB = $connectionPool->getQueryBuilderForTable(
            'tx_gdprextensionscomgm_domain_model_map'
        );
            if($multiLocationResult){

        $locationResult = $mapLocationQB->select('*')
            ->from('tx_gdprextensionscomgm_domain_model_map')
            ->where(
                $mapLocationQB->expr()
                    ->eq('dashboard_api_key', $mapLocationQB->createNamedParameter($multiLocationResult['dashboard_api_key'])),
            )
            ->orderBy('title', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative();
            }
         $messages = [];
        // Check if "gdpr_extensions_com_cm" is installed
        if (!in_array('gdpr_extensions_com_cm', $installedExtensions)) {
            array_push($messages,'Gdpr extensions consent manager is not installed! ');
        } 
        $UpdateOwnStatusTask = 0;
        $SyncMapTask = 0;
        
        if(!$multiLocationResult){
            array_push($messages,'Valid Api Key Not Added!');
        }
            if($multiLocationResult){
            foreach ($result as $schedulerTask){
                 if ($taskSerializer) {
                    $taskClassName = $taskSerializer->extractClassName($schedulerTask['serialized_task_object']);
                } else {
                    $taskObject = unserialize($schedulerTask['serialized_task_object']);
                    if ($taskObject instanceof AbstractTask) {
                        $taskClassName = get_class($taskObject);
                    } else {
                        continue;
                    }
                }
                if($taskClassName == 'GdprExtensionsCom\GdprExtensionsComCm\Commands\UpdateOwnStatusTask'){
                    $UpdateOwnStatusTask = 1;
                    $updateOwnLastExecTime = $schedulerTask['lastexecution_time'];

                    if($multiLocationResult['api_create_time'] > $updateOwnLastExecTime){
                        // need to add flag here
                        array_push($messages,'PLease run Update Website Status Scheduler!');
                    }
                }
                if($taskClassName == 'GdprExtensionsCom\GdprExtensionsComGm\Commands\SyncMapsTask'){
                    $SyncMapTask = 1;
                    $mapTaskLastExecTime = $schedulerTask['lastexecution_time'];
                    if($multiLocationResult['api_create_time'] > $mapTaskLastExecTime){
                        array_push($messages,'PLease run Fetch Google Map Scheduler!');

                    }else{
                        if (count($locationResult) <= 0){
                            array_push($messages, 'No Map Found');
                        }
                    }
                }

            }

            if(!$UpdateOwnStatusTask){
            array_push($messages,'PLease add Update Website Status Scheduler!');
            }
            if(!$SyncMapTask){
                array_push($messages,'PLease add Fetch Google Map Scheduler!');
            }
        }
        
        $string = '';
        $count = 1;
        foreach ($messages as $item) {
            $string .= "(".$count++.")"." - ".$item."   \n";
        }
        if (!$string){
            
            return true;
        }
    }
    

}

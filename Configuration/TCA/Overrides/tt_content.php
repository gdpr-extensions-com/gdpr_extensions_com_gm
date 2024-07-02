<?php
defined('TYPO3') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'GdprExtensionsComGm',
    'gdprextensionscommap',
    'Google Map'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    [
        'gdpr_map_business_locations' => [
            'displayCond' => 'USER:GdprExtensionsCom\GdprExtensionsComGm\Utility\ProcessMultiLocation->errorMssgLoc',
            'config' => [
                'type' => 'select',
                'maxitems' => 200,
                'renderType' => 'selectMultipleSideBySide',
                'itemsProcFunc' => 'GdprExtensionsCom\GdprExtensionsComGm\Utility\ProcessMultiLocation->getLocationsforRoodPid',
            ],
        ],
    ],
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    [
        'message' => [
            'displayCond' => 'USER:GdprExtensionsCom\GdprExtensionsComGm\Utility\ProcessMultiLocation->errorMssg',
            'exclude' => true,
            'config' => [
                'type' => 'none',
                'size' => 200,
                'format' => 'user',
                'format.' => [
                    'userFunc' => 'GdprExtensionsCom\GdprExtensionsComGm\Utility\ProcessMultiLocation->getErrorMssg',

                ],
            ],
        ],
    ],
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'gdpr_map_business_locations'
);


$GLOBALS['TCA']['tt_content']['types']['gdprextensionscomgm_gdprextensionscommap'] =[
    'showitem' => '
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
         --palette--;;general,
         gdpr_map_business_locations; Business Location,
         message; Error Message,
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
         --palette--;;hidden,
         --palette--;;access,
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
         space_before_class,
         space_after_class,
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
      sys_language_uid,
    ',
];
<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_map',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,iicon_path,map_path',
        'iconfile' => 'EXT:gdpr_extensions_com_gm/Resources/Public/Icons/tx_gdprextensionscomgm_domain_model_map.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'title, icon_path, map_path, locations, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_gdprextensionscomgm_domain_model_map',
                'foreign_table_where' => 'AND {#tx_gdprextensionscomgm_domain_model_map}.{#pid}=###CURRENT_PID### AND {#tx_gdprextensionscomgm_domain_model_map}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_map.title',
            'description' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_map.title.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'icon_path' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_map.iicon_path',
            'description' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_map.iicon_path.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'map_path' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_map.map_path',
            'description' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_map.map_path.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'locations' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_map.locations',
            'description' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_map.locations.description',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_gdprextensionscomgm_domain_model_maplocation',
                'foreign_field' => 'map',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],

        ],
        'lat' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_maplocation.lat',
            'description' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_maplocation.lat.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int',
                'default' => 0
            ],
        ],
        'lon' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_maplocation.lon',
            'description' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_maplocation.lon.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int',
                'default' => 0
            ],
        ],
        'zoom' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_maplocation.lon',
            'description' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomgm_domain_model_maplocation.lon.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int',
                'default' => 0
            ],
        ],
    ],
];

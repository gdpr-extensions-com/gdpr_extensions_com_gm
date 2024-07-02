<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'GdprExtensionsComGm',
        'gdprextensionscommap',
        [
            \GdprExtensionsCom\GdprExtensionsComGm\Controller\MapController::class => 'index'
        ],
        // non-cacheable actions
        [
            \GdprExtensionsCom\GdprExtensionsComGm\Controller\MapController::class => 'index'
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );


    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\GdprExtensionsCom\GdprExtensionsComGm\Commands\SyncMapsTask::class] = [
        'extension' => 'gdpr_extensions_com_gm',
        'title' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang.xlf:schedular_title',
        'description' => 'LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang.xlf:schedular_desc',
        'additionalFields' => \GdprExtensionsCom\GdprExtensionsComGm\Commands\SyncMapsTask::class,
    ];

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.common {
                elements {
                    gdprextensionscommap {
                        iconIdentifier = gdpr_extensions_com_gm-plugin-gdprextensionscommap
                        title = LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_gm_gdprextensionscommap.name
                        description = LLL:EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_gm_gdprextensionscommap.description
                        tt_content_defValues {
                            CType = gdprextensionscomgm_gdprextensionscommap
                        }
                    }
                }
                show = *
            }
        }'
    );

    
})();

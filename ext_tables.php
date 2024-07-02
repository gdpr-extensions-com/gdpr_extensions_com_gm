<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_gdprextensionscomgm_domain_model_map', 'EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_csh_tx_gdprextensionscomgm_domain_model_map.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gdprextensionscomgm_domain_model_map');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_gdprextensionscomgm_domain_model_maplocation', 'EXT:gdpr_extensions_com_gm/Resources/Private/Language/locallang_csh_tx_gdprextensionscomgm_domain_model_maplocation.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gdprextensionscomgm_domain_model_maplocation');
})();

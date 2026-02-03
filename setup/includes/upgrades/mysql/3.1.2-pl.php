<?php

/**
 * Specific upgrades for Revolution 3.1.2-pl
 *
 * @var modX $modx
 * @var modInstallVersion $this
 * @package setup
 * @subpackage upgrades
 */

/* Repair any datetime data that can trigger errors when
      NO_ZERO_DATE/NO_ZERO_IN_DATE sql_modes are enabled */

$updated = $modx->updateCollection(
    \MODX\Revolution\Transport\modTransportPackage::class,
    [
        'installed' => null
    ],
    [
        'installed:<' => '1000-01-01 00:00:00.000000'
    ]
);
if ($updated === false) {
    $this->runner->addResult(modInstallRunner::RESULT_FAILURE, $this->install->lexicon('transport_package_installed_update_invalid_dates_error'));
} else {
    $this->runner->addResult(modInstallRunner::RESULT_SUCCESS, $this->install->lexicon('transport_package_installed_update_invalid_dates_success'));
}

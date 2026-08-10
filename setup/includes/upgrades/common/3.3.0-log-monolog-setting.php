<?php

/**
 * Add the log_monolog system setting (default off) for Monolog opt-in.
 *
 * @var modX $modx
 * @var modInstallVersion $this
 * @package setup
 * @subpackage upgrades
 */

use MODX\Revolution\modSystemSetting;

$key = 'log_monolog';
$existing = $modx->getObject(modSystemSetting::class, ['key' => $key]);
if ($existing instanceof modSystemSetting) {
    return;
}

$setting = $modx->newObject(modSystemSetting::class);
$setting->fromArray([
    'key' => $key,
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'system',
], '', true, true);

if ($setting->save()) {
    $this->runner->addResult(
        modInstallRunner::RESULT_SUCCESS,
        '<p class="ok">Added system setting: ' . $key . '</p>'
    );
} else {
    $this->runner->addResult(
        modInstallRunner::RESULT_FAILURE,
        '<p class="notok">Could not add system setting: ' . $key . '</p>'
    );
}

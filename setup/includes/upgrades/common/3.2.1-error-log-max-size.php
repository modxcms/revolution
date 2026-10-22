<?php

/**
 * Add error_log_max_size system setting for limiting error log file size.
 *
 * @var modInstallVersion $this
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

use MODX\Revolution\modSystemSetting;

$setting = $modx->getObject(modSystemSetting::class, ['key' => 'error_log_max_size']);
if (!$setting) {
    $setting = $modx->newObject(modSystemSetting::class);
    $setting->fromArray([
        'key' => 'error_log_max_size',
        'value' => '0',
        'xtype' => 'numberfield',
        'namespace' => 'core',
        'area' => 'system',
    ], '', true, true);
    if ($setting->save()) {
        $messageTemplate = '<p class="%s">%s</p>';
        $this->runner->addResult(
            modInstallRunner::RESULT_SUCCESS,
            sprintf($messageTemplate, 'ok', 'System Setting `error_log_max_size` added.')
        );
    }
}

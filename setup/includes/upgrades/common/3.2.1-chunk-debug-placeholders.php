<?php
/**
 * Add chunk_debug_placeholders system setting (default off).
 *
 * @var modX $modx
 */

use MODX\Revolution\modSystemSetting;
use MODX\Revolution\modX;

$messageTemplate = '<p class="%s">%s</p>';
$key = 'chunk_debug_placeholders';

/** @var modSystemSetting|null $setting */
$setting = $modx->getObject(modSystemSetting::class, ['key' => $key]);
if ($setting instanceof modSystemSetting) {
    return;
}

$setting = $modx->newObject(modSystemSetting::class);
$setting->fromArray([
    'key' => $key,
    'value' => '0',
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'system',
], '', true, true);

if ($setting->save()) {
    $this->runner->addResult(
        modInstallRunner::RESULT_SUCCESS,
        sprintf($messageTemplate, 'ok', $this->install->lexicon('system_setting_update_success', ['key' => $key]))
    );
} else {
    $this->runner->addResult(
        modInstallRunner::RESULT_WARNING,
        sprintf($messageTemplate, 'warning', $this->install->lexicon('system_setting_update_failed', ['key' => $key]))
    );
}

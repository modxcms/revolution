<?php

/**
 * Add proxy_type system setting for SOCKS5 support
 *
 * @var modX $modx
 * @var modInstallRunner $this
 * @package setup
 * @subpackage upgrades
 */

use MODX\Revolution\modSystemSetting;

$messageTemplate = '<p class="%s">%s</p>';

$setting = $modx->getObject(modSystemSetting::class, ['key' => 'proxy_type']);
if (!$setting) {
    $setting = $modx->newObject(modSystemSetting::class);
    $setting->fromArray([
        'key' => 'proxy_type',
        'value' => 'HTTP',
        'xtype' => 'textfield',
        'namespace' => 'core',
        'area' => 'proxy',
    ]);
    if ($setting->save()) {
        $msg = $this->install->lexicon('system_setting_update_success', ['key' => 'proxy_type']);
        $this->runner->addResult(modInstallRunner::RESULT_SUCCESS, sprintf($messageTemplate, 'ok', $msg));
    } else {
        $msg = $this->install->lexicon('system_setting_update_failed', ['key' => 'proxy_type']);
        $this->runner->addResult(modInstallRunner::RESULT_ERROR, sprintf($messageTemplate, 'error', $msg));
    }
}

<?php

/**
 * Update the keys for smptp system settings
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modSystemSetting;

$settingKeysMap = [
    [
        'old_key' => 'mail_smtp_prefix',
        'new_key' => 'mail_smtp_secure',
    ]
];

$messageTemplate = '<p class="%s">%s</p>';

foreach ($settingKeysMap as $setting) {
    /** @var modSystemSetting $systemSetting */
    $systemSetting = $modx->getObject(modSystemSetting::class, [
        'key' => $setting['old_key']
    ]);
    if ($systemSetting instanceof modSystemSetting) {
        $systemSetting->set('key', $setting['new_key']);
        if ($systemSetting->save()) {
            $this->runner->addResult(
                modInstallRunner::RESULT_SUCCESS,
                sprintf($messageTemplate, 'ok', $this->install->lexicon('system_setting_rename_key_success', $setting))
            );
        } else {
            $this->runner->addResult(
                modInstallRunner::RESULT_ERROR,
                sprintf($messageTemplate, 'error', $this->install->lexicon('system_setting_rename_key_failure', $setting))
            );
        }
    }
}

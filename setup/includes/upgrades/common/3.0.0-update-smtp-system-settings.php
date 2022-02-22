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
    /** @var modSystemSetting $existingSetting */
    $existingSetting = $modx->getObject(modSystemSetting::class, [
        'key' => $setting['old_key']
    ]);
    $newSetting = $modx->getObject(modSystemSetting::class, [
        'key' => $setting['new_key']
    ]);
    if ($existingSetting instanceof modSystemSetting) {
        if ($newSetting instanceof modSystemSetting) {
            $newSetting->set('value', $existingSetting->get('value'));
            if ($newSetting->save()) {
                $this->runner->addResult(
                    modInstallRunner::RESULT_SUCCESS,
                    sprintf($messageTemplate, 'ok', $this->install->lexicon('system_setting_update_success', $newSetting->toArray()))
                );
                $existingSetting->remove();
            } else {
                $this->runner->addResult(
                    modInstallRunner::RESULT_ERROR,
                    sprintf($messageTemplate, 'error', $this->install->lexicon('system_setting_update_failed', $newSetting->toArray()))
                );
            }
        } else {
            $existingSetting->set('key', $setting['new_key']);
            if ($existingSetting->save()) {
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
}

<?php

/**
 * Remove legacy MODX 2.x system settings replaced by upload_file_exists in MODX 3.
 * Migrates a legacy value only when upload_file_exists is missing; never overwrites
 * an existing MODX 3 setting the admin may already have changed.
 *
 * @var modX $modx
 */

use MODX\Revolution\modSystemSetting;

$settings = [
    'upload_check_exists',
    'upload_check_exist',
];

$messageTemplate = '<p class="%s">%s</p>';
$replacementKey = 'upload_file_exists';
/** @var modSystemSetting|null $replacement */
$replacement = $modx->getObject(modSystemSetting::class, ['key' => $replacementKey]);
$valueMigrated = $replacement instanceof modSystemSetting;

foreach ($settings as $key) {
    /** @var modSystemSetting|null $setting */
    $setting = $modx->getObject(modSystemSetting::class, ['key' => $key]);
    if (!($setting instanceof modSystemSetting)) {
        continue;
    }

    if (!$valueMigrated) {
        $setting->set('key', $replacementKey);
        if (!$setting->save()) {
            $msg = $this->install->lexicon('system_setting_update_failed', ['key' => $replacementKey]);
            $this->runner->addResult(
                modInstallRunner::RESULT_WARNING,
                sprintf($messageTemplate, 'warning', $msg)
            );
            continue;
        }

        $replacement = $setting;
        $valueMigrated = true;
        $msg = $this->install->lexicon('system_setting_update_success', ['key' => $replacementKey]);
        $this->runner->addResult(
            modInstallRunner::RESULT_SUCCESS,
            sprintf($messageTemplate, 'ok', $msg)
        );
        continue;
    }

    if ($setting === $replacement) {
        continue;
    }

    if ($setting->remove()) {
        $msg = $this->install->lexicon('system_setting_cleanup_success', ['key' => $key]);
        $this->runner->addResult(
            modInstallRunner::RESULT_SUCCESS,
            sprintf($messageTemplate, 'ok', $msg)
        );
    } else {
        $msg = $this->install->lexicon('system_setting_cleanup_failed', ['key' => $key]);
        $this->runner->addResult(
            modInstallRunner::RESULT_WARNING,
            sprintf($messageTemplate, 'warning', $msg)
        );
    }
}

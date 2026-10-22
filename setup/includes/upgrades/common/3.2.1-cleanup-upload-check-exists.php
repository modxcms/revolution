<?php

/**
 * Remove legacy MODX 2.x system settings replaced by upload_file_exists in MODX 3.
 * After a normal 2.x→3.x upgrade the MODX 3 setting already exists; these orphans
 * only linger without lexicon descriptions (#15877).
 *
 * @var modX $modx
 */

use MODX\Revolution\modSystemSetting;

$settings = [
    'upload_check_exists',
    'upload_check_exist',
];

$messageTemplate = '<p class="%s">%s</p>';

foreach ($settings as $key) {
    /** @var modSystemSetting|null $setting */
    $setting = $modx->getObject(modSystemSetting::class, ['key' => $key]);
    if (!($setting instanceof modSystemSetting)) {
        continue;
    }

    if ($setting->remove()) {
        $this->runner->addResult(
            modInstallRunner::RESULT_SUCCESS,
            sprintf(
                $messageTemplate,
                'ok',
                $this->install->lexicon('system_setting_cleanup_success', ['key' => $key])
            )
        );
    } else {
        $this->runner->addResult(
            modInstallRunner::RESULT_WARNING,
            sprintf(
                $messageTemplate,
                'warning',
                $this->install->lexicon('system_setting_cleanup_failed', ['key' => $key])
            )
        );
    }
}

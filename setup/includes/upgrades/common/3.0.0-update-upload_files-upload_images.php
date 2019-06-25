<?php
/**
 * Common upgrade script for modify upload_files, upload_images System Setting
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modSystemSetting;

$messageTemplate = '<p class="%s">%s</p>';

$keys = ['upload_files','upload_images'];

foreach ($keys as $key) {
    $success = false;

    /** @var modSystemSetting $setting */
    $setting = $modx->getObject(modSystemSetting::class, array('key' => $key));
    if ($setting) {
        $value = $setting->get('value');
        $tmp = explode(',', $value);
        if (in_array('webp', $tmp)) {
            continue;
        } else {
            $setting->set('value', $value . ',webp');
            if ($setting->save()) {
                $success = true;
            }
        }
    }

    if ($success) {
        $this->runner->addResult(modInstallRunner::RESULT_SUCCESS,
            sprintf($messageTemplate, 'ok', $this->install->lexicon('system_setting_update_success', ['key' => $key])));
    } else {
        $this->runner->addResult(modInstallRunner::RESULT_WARNING,
            sprintf($messageTemplate, 'warning', $this->install->lexicon('system_setting_update_failed', ['key' => $key])));
    }
}

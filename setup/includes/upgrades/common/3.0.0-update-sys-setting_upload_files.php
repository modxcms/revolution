<?php

/**
 * Common upgrade script for modify upload_files System Setting
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modSystemSetting;

$messageTemplate = '<p class="%s">%s</p>';

$keysRemove = ['upload_images', 'upload_media'];

/** @var modSystemSetting $setting */
$upload_files_setting = $modx->getObject(modSystemSetting::class, ['key' => 'upload_files']);

// Update upload_files
if ($upload_files_setting) {
    $success = false;

    $upload_files_value = $upload_files_setting->get('value');
    $upload_files_arr = explode(',', $upload_files_value);

    // Adding webp, woff2
    if (!in_array('webp', $upload_files_arr) || !in_array('woff2', $upload_files_arr)) {
        $upload_files_setting->set('value', $upload_files_value . ',webp,woff2');
        $upload_files_setting->save();
    }

    // Collecting all extensions in upload_files, upload_images, upload_media
    $upload_images_setting = $modx->getObject(modSystemSetting::class, ['key' => 'upload_images']);
    $upload_media_setting = $modx->getObject(modSystemSetting::class, ['key' => 'upload_media']);

    $upload_images_arr = $upload_media_arr = [];
    if ($upload_images_setting) {
        $upload_images_value = $upload_images_setting->get('value');
        $upload_images_arr = explode(',', $upload_images_value);
    }

    if ($upload_media_setting) {
        $upload_media_value = $upload_media_setting->get('value');
        $upload_media_arr = explode(',', $upload_media_value);
    }

    $upload_files_arr_new = array_unique(array_merge($upload_files_arr, $upload_images_arr, $upload_media_arr));
    $upload_files_arr_new = array_filter($upload_files_arr_new);
    sort($upload_files_arr_new);

    $upload_files_value_new = implode(',', $upload_files_arr_new);
    $upload_files_setting->set('value', $upload_files_value_new);
    if ($upload_files_setting->save()) {
        $success = true;
    }

    if ($success) {
        $this->runner->addResult(
            modInstallRunner::RESULT_SUCCESS,
            sprintf($messageTemplate, 'ok', $this->install->lexicon('system_setting_update_success', ['key' => 'upload_files']))
        );
    } else {
        $this->runner->addResult(
            modInstallRunner::RESULT_WARNING,
            sprintf($messageTemplate, 'warning', $this->install->lexicon('system_setting_update_failed', ['key' => 'upload_files']))
        );
    }
}

// Remove upload_images, upload_media
foreach ($keysRemove as $key) {
    /** @var modSystemSetting $setting */
    $setting = $modx->getObject(modSystemSetting::class, ['key' => $key]);
    if ($setting instanceof modSystemSetting) {
        if ($setting->remove()) {
            $this->runner->addResult(
                modInstallRunner::RESULT_SUCCESS,
                sprintf($messageTemplate, 'ok', $this->install->lexicon('system_setting_cleanup_success', ['key' => $key]))
            );
        } else {
            $this->runner->addResult(
                modInstallRunner::RESULT_WARNING,
                sprintf($messageTemplate, 'warning', $this->install->lexicon('system_setting_cleanup_failure', ['key' => $key]))
            );
        }
    }
}

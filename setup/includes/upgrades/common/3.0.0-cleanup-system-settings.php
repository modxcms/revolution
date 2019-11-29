<?php
/**
 * Remove obsolete system settings about compression
 */

use MODX\Revolution\modSystemSetting;

$settings = [
    'compress_js_max_files',
    'manager_js_zlib_output_compression',
    'editor_css_path',
    'editor_css_selectors',
    'fe_editor_lang',
    'udperms_allowroot',
    'webpwdreminder_message',
    'allow_tv_eval',
    'upload_flash',
];

$messageTemplate = '<p class="%s">%s</p>';

foreach ($settings as $key) {
    /** @var modSystemSetting $setting */
    $setting = $modx->getObject(modSystemSetting::class, ['key' => $key]);
    if ($setting instanceof modSystemSetting) {
        if ($setting->remove()) {
            $this->runner->addResult(modInstallRunner::RESULT_SUCCESS,
                sprintf($messageTemplate, 'ok', $this->install->lexicon('system_setting_cleanup_success', ['key' => $key])));
        } else {
            $this->runner->addResult(modInstallRunner::RESULT_WARNING,
                sprintf($messageTemplate, 'warning', $this->install->lexicon('system_setting_cleanup_failure', ['key' => $key])));
        }
    }
}

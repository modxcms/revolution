<?php
/**
 * Remove obsolete system settings about compression
 */

use MODX\Revolution\modSystemSetting;

$settings = [
    'allow_tv_eval',
    'cache_action_map',
    'compress_js_max_files',
    'editor_css_path',
    'editor_css_selectors',
    'fe_editor_lang',
    'filemanager_path',
    'filemanager_path_relative',
    'filemanager_url',
    'filemanager_url_relative',
    'manager_js_cache_file_locking',
    'manager_js_cache_max_age',
    'manager_js_document_root',
    'manager_js_zlib_output_compression',
    'rb_base_dir',
    'rb_base_url',
    'resolve_hostnames',
    'server_protocol',
    'strip_image_paths',
    'udperms_allowroot',
    'upload_flash',
    'use_browser',
    'webpwdreminder_message',
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

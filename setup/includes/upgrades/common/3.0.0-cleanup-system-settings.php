<?php
/**
 * Remove obsolete system settings about compression
 */

$settings = [
    'compress_js_max_files',
    'manager_js_zlib_output_compression'
];

$messageTemplate = '<p class="%s">%s</p>';

foreach ($settings as $key) {
    /** @var modSystemSetting $setting */
    $setting = $modx->getObject('modSystemSetting', ['key' => $key]);
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

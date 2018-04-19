<?php
/**
 * Remove obsolete system settings about compression
 */

$settings = ['compress_js_max_files', 'manager_js_zlib_output_compression'];

foreach ($settings as $key) {
    /** @var modSystemSetting $setting */
    $setting = $modx->getObject('modSystemSetting', ['key' => $key]);
    if ($setting instanceof modSystemSetting) {
        if ($setting->remove()) {
            $this->runner->addResult(modInstallRunner::RESULT_SUCCESS,
                '<p class="ok">' . $key . ' System Setting removed.</p>');
        } else {
            $this->runner->addResult(modInstallRunner::RESULT_FAILURE,
                '<p class="notok">' . $key . ' System Setting could not be removed.</p>');
        }
    } else {
        $this->runner->addResult(modInstallRunner::RESULT_WARNING,
            '<p class="warning">' . $key . ' System Setting was not found.</p>');
    }
}

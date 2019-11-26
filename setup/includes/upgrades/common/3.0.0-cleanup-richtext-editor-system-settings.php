<?php
/**
 * Remove unused system settings for "Rich-Text Editor" section
 */

use MODX\Revolution\modSystemSetting;

$settings = [
    'editor_css_path',
    'editor_css_selectors'
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

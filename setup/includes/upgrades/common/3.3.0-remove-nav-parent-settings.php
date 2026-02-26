<?php

/**
 * Remove main_nav_parent and user_nav_parent system settings.
 * Menu position is configured in the Menu section; these settings caused confusion.
 *
 * @var modInstallVersion $this
 * @var modX $modx
 * @package setup
 * @see https://github.com/modxcms/revolution/issues/15972
 */

use MODX\Revolution\modSystemSetting;

$settings = ['main_nav_parent', 'user_nav_parent'];
$messageTemplate = '<p class="%s">%s</p>';

foreach ($settings as $key) {
    /** @var modSystemSetting $setting */
    $setting = $modx->getObject(modSystemSetting::class, ['key' => $key]);
    if ($setting instanceof modSystemSetting) {
        if ($setting->remove()) {
            $msg = $this->install->lexicon('system_setting_cleanup_success', ['key' => $key]);
            $this->runner->addResult(
                modInstallRunner::RESULT_SUCCESS,
                sprintf($messageTemplate, 'ok', $msg)
            );
        } else {
            $msg = $this->install->lexicon('system_setting_cleanup_failure', ['key' => $key]);
            $this->runner->addResult(
                modInstallRunner::RESULT_WARNING,
                sprintf($messageTemplate, 'warning', $msg)
            );
        }
    }
}

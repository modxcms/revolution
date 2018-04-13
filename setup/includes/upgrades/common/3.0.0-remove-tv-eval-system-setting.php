<?php
/**
 * Common upgrade script to clean up deprecated system settings for TV eval feature.
 *
 * @var modX
 *
 * @package setup
 */

$deprecatedSetting = $modx->getObject('modSystemSetting', array(
    'key' => 'allow_tv_eval'
));
if ($deprecatedSetting instanceof modSystemSetting) {
    if ($deprecatedSetting->remove()) {
        $this->addResult(modInstallRunner::RESULT_SUCCESS,'<p class="ok">allow_tv_eval System Setting removed.</p>');
    } else {
        $this->addResult(modInstallRunner::RESULT_FAILURE,'<p class="notok">allow_tv_eval System Setting could not be removed.</p>');
    }
} else {
    $this->runner->addResult(modInstallRunner::RESULT_WARNING,'<p class="warning">allow_tv_eval System Setting was not found.</p>');
}

<?php
/**
 * Add new system setting for the default media source type.
 *
 * @var modX
 */

$messageTemplate = '<p class="%s">%s</p>';

$defaultMediaSourceTypeSystemSetting = $modx->getObject('modSystemSetting', [
    'key' => 'default_media_source_type',
]);
if ($defaultMediaSourceTypeSystemSetting === null) {
    $defaultMediaSourceTypeSystemSetting = $modx->newObject('modSystemSetting');
    $defaultMediaSourceTypeSystemSetting->fromArray([
        'key' => 'default_media_source_type',
        'value' => 'sources.modFileMediaSource',
        'xtype' => 'modx-combo-source-type',
        'namespace' => 'core',
        'area' => 'manager',
    ]);

    if ($defaultMediaSourceTypeSystemSetting->save()) {
        $this->runner->addResult(modInstallRunner::RESULT_SUCCESS, sprintf($messageTemplate, 'ok', $this->install->lexicon('media_source_type_system_setting_success')));
    } else {
        $this->runner->addResult(modInstallRunner::RESULT_WARNING, sprintf($messageTemplate, 'warning', $this->install->lexicon('media_source_type_system_setting_success')));
    }
}

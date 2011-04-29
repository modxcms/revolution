<?php
/**
 * Updates a context setting
 *
 * @param string $context_key The key of the context
 * @param string $key The key of the setting
 * @param string $value The value of the setting.
 *
 * @package modx
 * @subpackage processors.context.setting
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

$context = $modx->getContext($scriptProperties['context_key']);
if ($context == null) return $modx->error->failure($modx->lexicon('setting_err_nf'));

if (!$context->checkPolicy('save')) return $modx->error->failure($modx->lexicon('permission_denied'));

$setting = $modx->getObject('modContextSetting',array(
    'key' => $scriptProperties['key'],
    'context_key' => $scriptProperties['context_key'],
));
if (!$setting) return $modx->error->failure($modx->lexicon('setting_err_nf'));

/* value parsing */
if ($scriptProperties['xtype'] == 'combo-boolean' && !is_numeric($scriptProperties['value'])) {
    if (in_array($scriptProperties['value'], array('yes', 'Yes', $modx->lexicon('yes'), 'true', 'True'))) {
        $scriptProperties['value'] = 1;
    } else $scriptProperties['value'] = 0;
}

$setting->set('key',$scriptProperties['key']);
$setting->set('context_key',$scriptProperties['context_key']);
$setting->set('value',$scriptProperties['value']);

$refreshURIs = false;
if ($setting->get('key') === 'friendly_urls' && $setting->isDirty('value') && $setting->get('value') == '1') {
    $refreshURIs = true;
}
if ($setting->get('key') === 'use_alias_path' && $setting->isDirty('value')) {
    $refreshURIs = true;
}
if ($setting->get('key') === 'container_suffix' && $setting->isDirty('value')) {
    $refreshURIs = true;
}

if ($setting->save() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

/* if friendly_urls is set on or use_alias_path changes, refreshURIs */
if ($refreshURIs) {
    $context->config[$setting->get('key')] = $setting->get('value');
    $modx->call('modResource', 'refreshURIs', array(&$modx, 0, array('contexts' => $context->get('key'))));
}

$modx->cacheManager->refresh(array(
    'db' => array(),
    'context_settings' => array('contexts' => array($context->get('key'))),
    'resource' => array('contexts' => array($context->get('key'))),
));

return $modx->error->success();
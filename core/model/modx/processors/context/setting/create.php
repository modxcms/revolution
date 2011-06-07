<?php
/**
 * Creates a context setting
 *
 * @param string $context_key/$fk The key of the context
 * @param string $key The key of the setting
 * @param string $value The value of the setting.
 * @param string $xtype (optional) The rendering type for the setting. Defaults
 * to textfield.
 * @param string $namespace (optional) The namespace of the setting. Defaults to
 * core.
 * @param string $area (optional) The area of the setting. Defaults to a blank
 * area.
 *
 * @package modx
 * @subpackage processors.context.setting
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');


/* get context */
$scriptProperties['context_key'] = isset($scriptProperties['fk']) ? $scriptProperties['fk'] : 0;
if (!$context = $modx->getContext($scriptProperties['context_key'])) return $modx->error->failure($modx->lexicon('setting_err_nf'));
if (!$context->checkPolicy('save')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* prevent duplicates */
$ae = $modx->getObject('modContextSetting',array(
    'key' => $scriptProperties['key'],
    'context_key' => $scriptProperties['context_key'],
));
if ($ae != null) $modx->error->addField('key',$modx->lexicon('setting_err_ae'));

/* prevent keys starting with numbers */
$nums = explode(',','1,2,3,4,5,6,7,8,9,0');
if (in_array(substr($scriptProperties['key'],0,1),$nums)) {
    $modx->error->addField('key',$modx->lexicon('setting_err_startint'));
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}


$setting= $modx->newObject('modContextSetting');
$setting->fromArray($scriptProperties,'',true);

$refreshURIs = false;
if ($setting->get('key') === 'friendly_urls' && $setting->get('value') == '1') {
    $refreshURIs = true;
}
if ($setting->get('key') === 'use_alias_path') {
    $refreshURIs = true;
}
if ($setting->get('key') === 'container_suffix') {
    $refreshURIs = true;
}

/* only set name/description lexicon entries if they dont exist
 * for context settings
 */
if (!empty($scriptProperties['name'])) {
    $entry = $modx->getObject('modLexiconEntry',array(
        'namespace' => $setting->get('namespace'),
        'topic' => 'default',
        'name' => 'setting_'.$scriptProperties['key'],
    ));
    if ($entry == null) {
        $entry = $modx->newObject('modLexiconEntry');
        $entry->set('namespace',$setting->get('namespace'));
        $entry->set('name','setting_'.$scriptProperties['key']);
        $entry->set('topic','default');
        $entry->set('value',$scriptProperties['name']);
        $entry->save();
        $entry->clearCache();
    }
}

if (!empty($scriptProperties['description'])) {
    $description = $modx->getObject('modLexiconEntry',array(
        'namespace' => $setting->get('namespace'),
        'topic' => 'default',
        'name' => 'setting_'.$scriptProperties['key'].'_desc',
    ));
    if ($description == null) {
        $description = $modx->newObject('modLexiconEntry');
        $description->set('namespace',$setting->get('namespace'));
        $description->set('name','setting_'.$scriptProperties['key'].'_desc');
        $description->set('topic','default');
        $description->set('value',$scriptProperties['description']);
        $description->save();
        $description->clearCache();
    }
}

/* save setting */
if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
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
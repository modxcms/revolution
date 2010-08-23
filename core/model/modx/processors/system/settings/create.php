<?php
/**
 * Create a system setting
 *
 * @param string $key The key to create
 * @param string $value The value of the setting
 * @param string $xtype The xtype for the setting, for rendering purposes
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 *
 * @package modx
 * @subpackage processors.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting','namespace');

/* get namespace */
if (empty($scriptProperties['namespace'])) $modx->error->addField('namespace',$modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$scriptProperties['namespace']);
if ($namespace == null) $modx->error->addField('namespace',$modx->lexicon('namespace_err_nf'));

/* prevent empty or already existing settings */
if (empty($scriptProperties['key'])) $modx->error->addField('key',$modx->lexicon('setting_err_ns'));
$ae = $modx->getObject('modSystemSetting',array(
    'key' => $scriptProperties['key'],
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

/* value parsing */
if ($scriptProperties['xtype'] == 'combo-boolean' && !is_numeric($scriptProperties['value'])) {
    if ($scriptProperties['value'] == 'yes' || $scriptProperties['value'] == 'Yes' || $scriptProperties['value'] == $modx->lexicon('yes')) {
        $scriptProperties['value'] = 1;
    } else {
        $scriptProperties['value'] = 0;
    }
}

$setting= $modx->newObject('modSystemSetting');
$setting->fromArray($scriptProperties,'',true);

/* set lexicon name/description */
$topic = $modx->getObject('modLexiconTopic',array(
    'name' => 'default',
    'namespace' => $setting->get('namespace'),
));
if ($topic == null) {
    $topic = $modx->newObject('modLexiconTopic');
    $topic->set('name','default');
    $topic->set('namespace',$setting->get('namespace'));
    $topic->save();
}


$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'name' => 'setting_'.$scriptProperties['key'],
));
if ($entry == null) {
    $entry = $modx->newObject('modLexiconEntry');
    $entry->set('namespace',$namespace->get('name'));
    $entry->set('name','setting_'.$scriptProperties['key']);
    $entry->set('value',$scriptProperties['name']);
    $entry->set('topic',$topic->get('id'));
    $entry->set('language',$modx->cultureKey);
    $entry->save();

    $entry->clearCache();
}
$description = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'name' => 'setting_'.$scriptProperties['key'].'_desc',
));
if ($description == null) {
    $description = $modx->newObject('modLexiconEntry');
    $description->set('namespace',$namespace->get('name'));
    $description->set('name','setting_'.$scriptProperties['key'].'_desc');
    $description->set('value',$scriptProperties['description']);
    $description->set('topic',$topic->get('id'));
    $description->set('language',$modx->cultureKey);
    $description->save();

    $description->clearCache();
}

if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

return $modx->error->success('',$setting);
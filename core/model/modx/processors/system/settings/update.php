<?php
/**
 * Update a system setting
 *
 * @param string $key The key of the setting
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

/* verify namespace */
if (empty($_POST['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

/* get setting */
if (empty($_POST['key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modSystemSetting',array(
    'key' => $_POST['key'],
));
if ($setting == null) return $modx->error->failure($modx->lexicon('setting_err_nf'));

/* value parsing */
if ($_POST['xtype'] == 'combo-boolean' && !is_numeric($_POST['value'])) {
    if ($_POST['value'] == 'yes' || $_POST['value'] == 'Yes' || $_POST['value'] == $modx->lexicon('yes')) {
        $_POST['value'] = 1;
    } else $_POST['value'] = 0;
}

$setting->fromArray($_POST,'',true);

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
    'name' => 'setting_'.$_POST['key'],
));
if ($entry == null) {
    $entry = $modx->newObject('modLexiconEntry');
    $entry->set('namespace',$namespace->get('name'));
    $entry->set('name','setting_'.$_POST['key']);
    $entry->set('topic',$topic->get('id'));
    $entry->set('language',$modx->cultureKey);
}
$entry->set('value',$_POST['name']);
$entry->save();
$entry->clearCache();

$description = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'name' => 'setting_'.$_POST['key'].'_desc',
));
if ($description == null) {
    $description = $modx->newObject('modLexiconEntry');
    $description->set('namespace',$namespace->get('name'));
    $description->set('name','setting_'.$_POST['key'].'_desc');
    $description->set('language',$modx->cultureKey);
    $description->set('topic',$topic->get('id'));
}
$description->set('value',$_POST['description']);
$description->save();
$description->clearCache();

/* save setting */
if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}


$modx->reloadConfig();

return $modx->error->success('',$setting);
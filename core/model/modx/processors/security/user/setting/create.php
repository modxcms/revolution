<?php
/**
 * Create a user setting
 *
 * @param integer $user/$fk The user to create the setting for
 * @param string $key The setting key
 * @param string $value The value of the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 *
 * @package modx
 * @subpackage processors.context.setting
 */
$modx->lexicon->load('setting','namespace');

/* get namespace */
if (empty($_POST['namespace'])) $modx->error->addField('namespace',$modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) $modx->error->addField('namespace',$modx->lexicon('namespace_err_nf'));

/* prevent keys starting with numbers */
$nums = explode(',','1,2,3,4,5,6,7,8,9,0');
if (in_array(substr($_POST['key'],0,1),$nums)) {
    $modx->error->addField('key',$modx->lexicon('setting_err_startint'));
}

/* prevent duplicate keys */
$ae = $modx->getObject('modUserSetting',array(
    'key' => $_POST['key'],
    'user' => $_POST['user'],
));
if ($ae != null) return $modx->error->failure($modx->lexicon('setting_err_ae'));


if ($modx->error->hasError) {
    return $modx->error->failure();
}



$_POST['user'] = isset($_POST['fk']) ? $_POST['fk'] : 0;

$setting= $modx->newObject('modUserSetting');
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


/* only set name/description lexicon entries if they dont exist
 * for user settings
 */
$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'name' => 'setting_'.$_POST['key'],
));
if ($entry == null) {
    $entry = $modx->newObject('modLexiconEntry');
    $entry->set('namespace',$namespace->get('name'));
    $entry->set('name','setting_'.$_POST['key']);
    $entry->set('value',$_POST['name']);
    $entry->set('topic',$topic->get('id'));
    $entry->save();
}
$description = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'name' => 'setting_'.$_POST['key'].'_desc',
));
if ($description == null) {
    $description = $modx->newObject('modLexiconEntry');
    $description->set('namespace',$namespace->get('name'));
    $description->set('name','setting_'.$_POST['key'].'_desc');
    $description->set('value',$_POST['description']);
    $description->set('topic',$topic->get('id'));
    $description->save();
}

if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

return $modx->error->success();
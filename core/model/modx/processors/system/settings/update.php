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
if (empty($scriptProperties['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$scriptProperties['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

/* get setting */
if (empty($scriptProperties['key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modSystemSetting',array(
    'key' => $scriptProperties['key'],
));
if ($setting == null) return $modx->error->failure($modx->lexicon('setting_err_nf'));

/* value parsing */
if ($scriptProperties['xtype'] == 'combo-boolean' && !is_numeric($scriptProperties['value'])) {
    if ($scriptProperties['value'] == 'yes' || $scriptProperties['value'] == 'Yes' || $scriptProperties['value'] == $modx->lexicon('yes')) {
        $scriptProperties['value'] = 1;
    } else $scriptProperties['value'] = 0;
}

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
    $entry->set('topic',$topic->get('id'));
    $entry->set('language',$modx->cultureKey);
}
$entry->set('value',$scriptProperties['name']);
$entry->save();
$entry->clearCache();

$description = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'name' => 'setting_'.$scriptProperties['key'].'_desc',
));
if ($description == null) {
    $description = $modx->newObject('modLexiconEntry');
    $description->set('namespace',$namespace->get('name'));
    $description->set('name','setting_'.$scriptProperties['key'].'_desc');
    $description->set('language',$modx->cultureKey);
    $description->set('topic',$topic->get('id'));
}
$description->set('value',$scriptProperties['description']);
$description->save();
$description->clearCache();

/* save setting */
if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}


$modx->reloadConfig();
$modx->cacheManager->deleteTree($modx->getOption('core_path',null,MODX_CORE_PATH).'cache/mgr/smarty/',array(
   'deleteTop' => false,
    'skipDirs' => false,
    'extensions' => array('.cache.php','.php'),
));

return $modx->error->success('',$setting);
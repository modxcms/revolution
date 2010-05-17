<?php
/**
 * Update a setting from a grid
 *
 * @param string $key The key of the setting
 * @param string $oldkey The old key of the setting
 * @param string $value The value of the setting
 * @param string $area The area for the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 *
 * @package modx
 * @subpackage processors.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

/* get data */
if (empty($scriptProperties['data'])) return $modx->error->failure();
$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get setting */
if (empty($_DATA['key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modSystemSetting',array(
    'key' => $_DATA['key'],
));
if ($setting == null) return $modx->error->failure($modx->lexicon('setting_err_nf'));

/* set new value */
$setting->set('value',$_DATA['value']);
$setting->set('area',$_DATA['area']);

/* if name changed, change lexicon string */
$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => $setting->get('namespace'),
    'name' => 'setting_'.$_DATA['oldkey'],
));
if ($entry != null) {
    $entry->set('value',$_DATA['name']);
    $entry->save();
    $entry->clearCache();
}

/* save setting */
if ($setting->save() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}


$modx->reloadConfig();
$modx->cacheManager->deleteTree($modx->getOption('core_path',null,MODX_CORE_PATH).'cache/mgr/smarty/',array(
   'deleteTop' => false,
    'skipDirs' => false,
    'extensions' => array('.cache.php','.php'),
));


return $modx->error->success('',$setting);
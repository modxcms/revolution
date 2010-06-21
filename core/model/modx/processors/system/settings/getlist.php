<?php
/**
 * Get a list of system settings
 *
 * @param string $key (optional) If set, will search by this value
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'key');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$key = $modx->getOption('key',$scriptProperties,'');
$namespace = $modx->getOption('namespace',$scriptProperties,'');
$area = $modx->getOption('area',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('modSystemSetting');
if (!empty($key)) {
    $c->leftJoin('modLexiconEntry','Entry','CONCAT("setting_",`modSystemSetting`.`key`) = `Entry`.`name`');
    $c->leftJoin('modLexiconEntry','Description','CONCAT("setting_",`modSystemSetting`.`key`,"_desc") = `Description`.`name`');
    $c->where(array(
        'modSystemSetting.key:LIKE' => '%'.$key.'%',
    ));
    $c->orCondition(array(
        'Entry.value:LIKE' => '%'.$key.'%',
    ));
    $c->orCondition(array(
        'modSystemSetting.value:LIKE' => '%'.$key.'%',
    ));
    $c->orCondition(array(
        'Description.value:LIKE' => '%'.$key.'%',
    ));
}

if (!empty($namespace)) {
    $c->where(array('namespace' => $namespace));
}
if (!empty($area)) {
    $c->where(array('area' => $area));
}
$count = $modx->getCount('modSystemSetting',$c);

$c->sortby('`modSystemSetting`.`area`','ASC');
$c->sortby('`modSystemSetting`.`'.$sort.'`',$dir);
if ($isLimit) $c->limit($limit,$start);

$settings = $modx->getCollection('modSystemSetting',$c);

$list = array();
foreach ($settings as $setting) {
    $settingArray = $setting->toArray();
    $k = 'setting_'.$settingArray['key'];

    /* if 3rd party setting, load proper text */
    $modx->lexicon->load($setting->get('namespace').':default');

    /* get translated area text */
    if ($modx->lexicon->exists('area_'.$setting->get('area'))) {
        $settingArray['area_text'] = $modx->lexicon('area_'.$setting->get('area'));
    } else {
        $settingArray['area_text'] = $settingArray['area'];
    }

    /* get translated name and description text */
    $settingArray['description'] = $modx->lexicon->exists($k.'_desc')
        ? $modx->lexicon($k.'_desc')
        : '';
    $settingArray['name'] = $modx->lexicon->exists($k)
        ? $modx->lexicon($k)
        : $settingArray['key'];


    $settingArray['oldkey'] = $settingArray['key'];
    
    $settingArray['editedon'] = $setting->get('editedon') == '-001-11-30 00:00:00' || $settingArray['editedon'] == '0000-00-00 00:00:00' || $settingArray['editedon'] == null
        ? ''
        : strftime('%b %d, %Y %I:%M %p',strtotime($setting->get('editedon')));

    $list[] = $settingArray;
}
return $this->outputArray($list,$count);
<?php
/**
 * Gets a list of user settings
 *
 * @param integer $user The user to grab from
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to key.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

/* setup default properties */
$isLimit = empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'key');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$user = $modx->getOption('user',$scriptProperties,0);
$key = $modx->getOption('key',$scriptProperties,false);

/* setup criteria and get settings */
$where = array(
    'user' => $user,
);
if ($key) $where['key:LIKE'] = '%'.$key.'%';

$c = $modx->newQuery('modUserSetting');
$c->select(array(
    $modx->getSelectColumns('modUserSetting','modUserSetting'),
));
$c->select(array(
    '`Entry`.`value` `name_trans`',
    '`Description`.`value` `description_trans`',
));
$c->leftJoin('modLexiconEntry','Entry','CONCAT("setting_",`modUserSetting`.`key`) = `Entry`.`name`');
$c->leftJoin('modLexiconEntry','Description','CONCAT("setting_",`modUserSetting`.`key`,"_desc") = `Description`.`name`');
$c->where($where);
$count = $modx->getCount('modUserSetting',$c);
$c->sortby($modx->getSelectColumns('modUserSetting','modUserSetting','',array('area')),'ASC');
$c->sortby($modx->getSelectColumns('modUserSetting','modUserSetting','',array($sort)),$dir);
if ($isLimit) $c->limit($limit,$start);
$settings = $modx->getCollection('modUserSetting',$c);

/* iterate through settings */
$list = array();
foreach ($settings as $setting) {
    $settingArray = $setting->toArray();
    $k = 'setting_'.$settingArray['key'];

    /* if 3rd party setting, load proper text */
    $modx->lexicon->load('en:'.$setting->get('namespace').':default');
    $modx->lexicon->load($setting->get('namespace').':default');

    /* set area text if has a lexicon string for it */
    if ($modx->lexicon->exists('area_'.$setting->get('area'))) {
        $settingArray['area_text'] = $modx->lexicon('area_'.$setting->get('area'));
    } else {
        $settingArray['area_text'] = $settingArray['area'];
    }

    /* get translated name and description text */
    if (empty($settingArray['description_trans'])) {
        if ($modx->lexicon->exists($k.'_desc')) {
            $settingArray['description_trans'] = $modx->lexicon($k.'_desc');
            $settingArray['description'] = $k.'_desc';
        } else {
            $settingArray['description_trans'] = $settingArray['description'];
        }
    } else {
        $settingArray['description'] = $settingArray['description_trans'];
    }
    if (empty($settingArray['name_trans'])) {
        if ($modx->lexicon->exists($k)) {
            $settingArray['name_trans'] = $modx->lexicon($k);
            $settingArray['name'] = $k;
        } else {
            $settingArray['name_trans'] = $settingArray['key'];
        }
    } else {
        $settingArray['name'] = $settingArray['name_trans'];
    }
    $settingArray['oldkey'] = $settingArray['key'];    
    $settingArray['editedon'] = $setting->get('editedon') == '-001-11-30 00:00:00' || $settingArray['editedon'] == '0000-00-00 00:00:00' || $settingArray['editedon'] == null
        ? ''
        : strftime('%b %d, %Y %I:%M %p',strtotime($setting->get('editedon')));


    $menu = array();
    $menu[] = array(
        'text' => $modx->lexicon('setting_remove'),
        'handler' => 'this.remove.createDelegate(this,["setting_remove_confirm"])',
    );
    $settingArray['menu'] = $menu;
    $list[] = $settingArray;
}
return $this->outputArray($list,$count);
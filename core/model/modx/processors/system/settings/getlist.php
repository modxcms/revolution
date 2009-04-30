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
$modx->lexicon->load('setting');

if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'key';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modSystemSetting');
$cc = $modx->newQuery('modSystemSetting');
if (isset($_REQUEST['key']) && $_REQUEST['key'] != '') {
    $c->leftJoin('modLexiconEntry','Entry','CONCAT("setting_",modSystemSetting.key) = Entry.name');
    $cc->leftJoin('modLexiconEntry','Entry','CONCAT("setting_",modSystemSetting.key) = Entry.name');

    $wa = array(
        'modSystemSetting.key:LIKE' => '%'.$_REQUEST['key'].'%',
    );
    $na = array(
        'Entry.value:LIKE' => '%'.$_REQUEST['key'].'%',
    );
    $va = array(
        'modSystemSetting.value:LIKE' => '%'.$_REQUEST['key'].'%',
    );
    $c->where($wa);
    $cc->where($wa);
    $c->orCondition($na);
    $cc->orCondition($na);
    $c->orCondition($va);
    $cc->orCondition($va);
}

if (isset($_REQUEST['namespace'])) {
    $c->where(array('namespace' => $_REQUEST['namespace']));
    $cc->where(array('namespace' => $_REQUEST['namespace']));
}

$c->sortby('`modSystemSetting`.`area`,`modSystemSetting`.`'.$_REQUEST['sort'].'`',$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);

$settings = $modx->getCollection('modSystemSetting',$c);
$count = $modx->getCount('modSystemSetting',$cc);

$ss = array();
foreach ($settings as $setting) {
    $sa = $setting->toArray();
    $k = 'setting_'.$sa['key'];


    /* if 3rd party setting, load proper text */
    $modx->lexicon->load($setting->get('namespace').':default');

    if ($modx->lexicon->exists('area_'.$setting->get('area'))) {
        $sa['area_text'] = $modx->lexicon('area_'.$setting->get('area'));
    } else $sa['area_text'] = $sa['area'];

    $sa['description'] = $modx->lexicon->exists($k.'_desc')
        ? $modx->lexicon($k.'_desc')
        : '';
    $sa['name'] = $modx->lexicon->exists($k)
        ? $modx->lexicon($k)
        : $sa['key'];
    $sa['oldkey'] = $sa['key'];
    $sa['editedon'] = $sa['editedon'] == '0000-00-00 00:00:00' || $sa['editedon'] == null
        ? ''
        : strftime('%b %d, %Y %I:%M %p',strtotime($setting->get('editedon')));

    $sa['menu'] = array(
        array(
            'text' => $modx->lexicon('setting_update'),
            'handler' => array( 'xtype' => 'modx-window-setting-update' ),
        ),
        '-',
        array(
            'text' => $modx->lexicon('setting_remove'),
            'handler' => 'this.remove.createDelegate(this,["setting_remove_confirm"])',
        ),
    );
    $ss[] = $sa;
}
return $this->outputArray($ss,$count);
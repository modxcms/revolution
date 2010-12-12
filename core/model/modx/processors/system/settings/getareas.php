<?php
/**
 * Get a list of setting areas
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

/* setup default properties */
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$namespace = $modx->getOption('namespace',$scriptProperties,'');

/* build query */
$subc = $modx->newQuery('modSystemSetting');
$subc->setClassAlias('AreaSS');
$subc->select(array(
    'COUNT('.$modx->escape('AreaSS').'.'.$modx->escape('key').')',
));
$subc->where(array(
    $modx->getSelectColumns('modSystemSetting','AreaSS','',array('area')).' = '.$modx->getSelectColumns('modSystemSetting','modSystemSetting','',array('area'))
));
$subc->prepare();
$sql = $subc->toSql();
$c = $modx->newQuery('modSystemSetting');
$c->select(array(
    $modx->escape('key'),
    'area',
));
$c->select('('.$sql.') AS '.$modx->escape('ct'));
if (!empty($namespace)) {
    $c->where(array(
        'modSystemSetting.namespace' => $namespace,
    ));
}
$c->groupby($modx->escape('area'));
$c->sortby($modx->escape('area'),$dir);
$areas = $modx->getCollection('modSystemSetting',$c);

$list = array();
foreach ($areas as $area) {
    $areaArray = $area->toArray();
    if ($area->get('namespace') != 'core') {
        $modx->lexicon->load($area->get('namespace').':default');
    }
    $name = $area->get('area');
    $lex = 'area_'.$name;
    if ($modx->lexicon->exists($lex)) {
        $name = $modx->lexicon($lex);
    }
    $list[] = array(
        'd' => $name.' ('.$area->get('ct').')',
        'v' => $area->get('area'),
    );
}
return $this->outputArray($list);
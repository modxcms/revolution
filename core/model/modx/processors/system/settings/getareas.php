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
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$namespace = $modx->getOption('namespace',$_REQUEST,'');

/* build query */
$c = $modx->newQuery('modSystemSetting');
$c->select('
    `modSystemSetting`.`key`,
    `modSystemSetting`.`area` AS `area`,
    IF(`Area`.`value` IS NULL,`modSystemSetting`.`area`,`Area`.`value`) AS `name`
');
$c->leftJoin('modLexiconEntry','Area','CONCAT("area_",`modSystemSetting`.`area`) = `Area`.`name`');
if (!empty($namespace)) {
    $c->where(array(
        'modSystemSetting.namespace' => $namespace,
    ));
}
$c->groupby('`area`');
$c->sortby('`area`',$dir);
if ($isLimit) $c->limit($limit,$start);

$areas = $modx->getCollection('modSystemSetting',$c);

$list = array();
foreach ($areas as $area) {
    $areaArray = $area->toArray();
    $list[] = array(
        'd' => $area->get('name'),
        'v' => $area->get('area'),
    );
}
return $this->outputArray($list,$count);
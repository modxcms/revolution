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
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$namespace = $modx->getOption('namespace',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('modSystemSetting');
$c->select('
    `modSystemSetting`.`key`,
    `modSystemSetting`.`area` AS `area`
');
//$c->leftJoin('modLexiconEntry','Area','CONCAT("area_",`modSystemSetting`.`area`) = `Area`.`name`');
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
    if ($area->get('namespace') != 'core') {
        $modx->lexicon->load($area->get('namespace').':default');
    }
    $name = $area->get('area');
    $lex = 'area_'.$name;
    if ($modx->lexicon->exists($lex)) {
        $name = $modx->lexicon($lex);
    }
    $list[] = array(
        'd' => $name,
        'v' => $area->get('area'),
    );
}
return $this->outputArray($list,$count);
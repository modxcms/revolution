<?php
/**
 * Grabs a list of actions
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to controller.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.action
 */
if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'controller');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$showNone = $modx->getOption('showNone',$scriptProperties,true);
/* get actions */
$c = $modx->newQuery('modAction');
$c->sortby($modx->getSelectColumns('modAction','modAction','',array('namespace')),'ASC');
$c->sortby($sort,$dir);
if ($isLimit) {
    $c->limit($limit,$start);
}
$actions = $modx->getIterator('modAction',$c);
$count = $modx->getCount('modAction');

$list = array();
if (!empty($showNone)) {
    $list[] = array('id' => 0, 'controller' => $modx->lexicon('action_none').$showNone);
}
foreach ($actions as $action) {
    $actionArray = $action->toArray();

    $controller = $actionArray['controller'];
    $controllerLength = strlen($controller);

    $actionArray['controller'] = $actionArray['namespace'].' - '.$actionArray['controller'];

    $list[] = $actionArray;
}
return $this->outputArray($list,$count);
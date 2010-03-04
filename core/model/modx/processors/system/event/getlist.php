<?php
/**
 * Gets a list of events
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.event
 */
if (!$modx->hasPermission('view_eventlog')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('system_event');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$c = $modx->newQuery('modEvent');
$count = $modx->getCount('modEvent',$c);
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$events = $modx->getCollection('modEvent',$c);

$list = array();
foreach ($events as $event) {
    $eventArray = $event->toArray();

    $list[] = $eventArray;
}
return $this->outputArray($list,$count);
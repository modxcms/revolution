<?php
/**
 * Grabs a list of workspaces
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace
 */
if (!$modx->hasPermission('workspaces')) return $modx->error->failure($modx->lexicon('permission_denied'));

$modx->lexicon->load('workspace');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* build query */
$c = $modx->newQuery('modWorkspace');
$count = $modx->getCount('modWorkspace',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$workspaces = $modx->getCollection('modWorkspace',$c);

/* iterate */
$list = array();
foreach ($workspaces as $workspace) {
    $workspaceArray = $workspace->toArray();
    $list[] = $workspaceArray;
}
return $this->outputArray($list,$count);

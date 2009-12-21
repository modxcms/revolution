<?php
/**
 * Gets a list of resources.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @return array An array of modResources
 *
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'pagetitle');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

/* query for resources */
$c = $modx->newQuery('modResource');
$count = $modx->getCount('modResource',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$resources = $modx->getCollection('modResource',$c);

/* iterate through resources */
$list = array();
foreach ($resources as $resource) {
    if ($resource->checkPolicy('list')) {
        $list[] = $resource->toArray();
    }
}
return $this->outputArray($list,$count);
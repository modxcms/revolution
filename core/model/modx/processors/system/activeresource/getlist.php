<?php
/**
 * Gets a list of active resources
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @param string $dateFormat (optional) The strftime date format to format the
 * editedon date to. Defaults to: %b %d, %Y %I:%M %p
 *
 * @package modx
 * @subpackage processors.system.activeresource
 */
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'editedon');
$dir = $modx->getOption('dir',$_REQUEST,'DESC');
$dateFormat = $modx->getOption('dateFormat',$_REQUEST,'%b %d, %Y %I:%M %p');

/* get resources */
$c = $modx->newQuery('modResource');
$c->innerJoin('modUser','EditedBy');
$c->where(array(
    'deleted' => 0,
    '`editedon` IS NOT NULL',
));
$total = $modx->getCount('modResource',$cc);
$c->select('
    `modResource`.*,
    `EditedBy`.`username` AS `username`
');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$resources = $modx->getCollection('modResource',$c);

/* iterate */
$list = array();
foreach ($resources as $resource) {
	$resourceArray = $resource->get(array(
        'id','pagetitle','editedon','username',
    ));
	$resourceArray['editedon'] = strftime($dateFormat,strtotime($resource->get('editedon')));
	$list[] = $resourceArray;
}
return $this->outputArray($list,$total);
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
$modx->lexicon->load('resource');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'pagetitle';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modResource');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$resources = $modx->getCollection('modResource',$c);

$cs = array();
foreach ($resources as $resource) {
    if ($resource->checkPolicy('list')) {
        $cs[] = $resource->toArray();
    }
}
return $this->outputArray($cs);
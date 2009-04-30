<?php
/**
 * Gets a list of active resources
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.activeresource
 */
$modx->lexicon->load('resource');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;

$c = $modx->newQuery('modResource');
$c->where(array('deleted' => 0));
$c->sortby('editedon','DESC');
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$resources = $modx->getCollection('modResource',$c);

$cc = $modx->newQuery('modResource');
$cc->where(array('deleted' => 0));
$total = $modx->getCount('modResource',$cc);

$rs = array();
foreach ($resources as $resource) {
	$editor = $modx->getObject('modUser',$resource->get('editedby'));
	$r = $resource->get(array_diff(array_keys($resource->_fields), array('content')));
	$r['editedon'] = strftime('%x %X',$r['editedon']);
	$r['user'] = $editor->get('username');
	$rs[] = $r;
}

return $this->outputArray($rs,$total);
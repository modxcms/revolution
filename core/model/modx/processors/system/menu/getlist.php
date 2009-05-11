<?php
/**
 * Get a list of menu items
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to menuindex.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.menu
 */
$modx->lexicon->load('action','menu','topmenu');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'menuindex';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modMenu');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
/* $c->limit($_REQUEST['limit'],$_REQUEST['start']); */
$menus = $modx->getCollection('modMenu',$c);

$count = $modx->getCount('modMenu');

$ms = array();

foreach ($menus as $menu) {
	$ma = $menu->toArray();
    $ma['text'] = $modx->lexicon($ma['text']);
    $ms[] = $ma;
}
return $this->outputArray($ms,$count);
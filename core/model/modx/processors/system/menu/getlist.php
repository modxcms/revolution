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
if (!$modx->hasPermission('menus')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu','topmenu');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'menuindex');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* get menus */
$c = $modx->newQuery('modMenu');
$c->sortby($scriptProperties['sort'],$scriptProperties['dir']);
if ($isLimit) $c->limit($scriptProperties['limit'],$scriptProperties['start']);

$menus = $modx->getCollection('modMenu',$c);
$count = $modx->getCount('modMenu');

$list = array();

foreach ($menus as $menu) {
    $menuArray = $menu->toArray();
    $menuArray['text_lex'] = $modx->lexicon($menuArray['text']);
    $list[] = $menuArray;
}
return $this->outputArray($list,$count);
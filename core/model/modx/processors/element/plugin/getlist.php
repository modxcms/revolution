<?php
/**
 * Grabs a list of plugins.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
$modx->lexicon->load('plugin');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* query plugins */
$c = $modx->newQuery('modPlugin');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$plugins = $modx->getCollection('modPlugin',$c);
$count = $modx->getCount('modPlugin');

/* iterate through plugins */
$list = array();
foreach ($plugins as $plugin) {
    if (!$plugin->checkPolicy('list')) continue;
    $list[] = $plugin->toArray();
}

return $this->outputArray($list,$count);
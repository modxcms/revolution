<?php
/**
 * Grabs a list of TVs.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.tv
 */
$modx->lexicon->load('tv');

/* get default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* query for tvs */
$c = $modx->newQuery('modTemplateVar');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$tvs = $modx->getCollection('modTemplateVar',$c);
$count = $modx->getCount('modTemplateVar');

/* iterate through tvs */
$list = array();
foreach ($tvs as $tv) {
    if (!$tv->checkPolicy('list')) continue;
    $list[] = $tv->toArray();
}

return $this->outputArray($list,$count);
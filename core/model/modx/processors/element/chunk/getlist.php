<?php
/**
 * Grabs a list of chunks.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
$modx->lexicon->load('chunk');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* query for chunks */
$c = $modx->newQuery('modChunk');
$count = $modx->getCount('modChunk');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$chunks = $modx->getCollection('modChunk',$c);

/* iterate through chunks */
$list = array();
foreach ($chunks as $chunk) {
    if (!$chunk->checkPolicy('list')) continue;
    $list[] = $chunk->toArray();
}

return $this->outputArray($list,$count);
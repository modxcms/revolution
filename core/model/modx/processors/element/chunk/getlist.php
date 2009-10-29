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
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('chunk');

/* setup default properties */
$isLimit = empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

/* query for chunks */
$c = $modx->newQuery('modChunk');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$chunks = $modx->getCollection('modChunk',$c);
$count = $modx->getCount('modChunk');

/* iterate through chunks */
$list = array();
foreach ($chunks as $chunk) {
    $list[] = $chunk->toArray();
}

return $this->outputArray($list,$count);
<?php
/**
 * Grabs a list of snippets.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
if (!$modx->hasPermission('view_snippet')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('snippet');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

/* query for snippets */
$c = $modx->newQuery('modSnippet');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$snippets = $modx->getCollection('modSnippet',$c);
$count = $modx->getCount('modSnippet');

/* iterate through snippets */
$list = array();
foreach ($snippets as $snippet) {
    $list[] = $snippet->toArray();
}

return $this->outputArray($list,$count);
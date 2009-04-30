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
$modx->lexicon->load('snippet');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modSnippet');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
    $c = $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$snippets = $modx->getCollection('modSnippet',$c);
$count = $modx->getCount('modSnippet');

$cs = array();
foreach ($snippets as $snippet) {
    $cs[] = $snippet->toArray();
}

return $this->outputArray($cs,$count);
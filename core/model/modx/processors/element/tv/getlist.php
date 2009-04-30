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

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modTemplateVar');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$tvs = $modx->getCollection('modTemplateVar',$c);
$count = $modx->getCount('modTemplateVar');

$cs = array();
foreach ($tvs as $tv) {
    $cs[] = $tv->toArray();
}

return $this->outputArray($cs,$count);
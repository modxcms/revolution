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

if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modPlugin');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if (isset($_REQUEST['limit'])) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}

$plugins = $modx->getCollection('modPlugin',$c);
$count = $modx->getCount('modPlugin');

$cs = array();
foreach ($plugins as $plugin) {
    $cs[] = $plugin->toArray();
}

return $this->outputArray($cs,$count);
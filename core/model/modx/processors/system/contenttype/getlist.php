<?php
/**
 * Gets a list of content types
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
$modx->lexicon->load('content_type');

if (!$modx->hasPermission('content_types')) return $modx->error->failure($modx->lexicon('permission_denied'));

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modContentType');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$types = $modx->getCollection('modContentType',$c);
$count = $modx->getCount('modContentType');

$cts = array();
foreach ($types as $type) {
    $cta = $type->toArray();
    $cta['menu'] = array(
        array(
            'text' => $modx->lexicon('content_type_remove'),
            'handler' => 'this.confirm.createDelegate(this,["remove","'.$modx->lexicon('content_type_remove_confirm').'"])'
        )
    );
    $cts[] = $cta;
}
return $this->outputArray($cts,$count);
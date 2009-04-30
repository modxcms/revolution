<?php
/**
 * Gets a list of namespaces
 *
 * @param string $name (optional) If set, will search by name
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
$modx->lexicon->load('workspace','namespace');

if (isset($_REQUEST['limit'])) $limit = true;
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';


$c = $modx->newQuery('modNamespace');
if (isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
    $c->where(array('name:LIKE' => '%'.$_REQUEST['name'].'%'));
}

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);
$namespaces = $modx->getCollection('modNamespace',$c);
$count = $modx->getCount('modNamespace');

$ps = array();
foreach ($namespaces as $namespace) {
    $pa = $namespace->toArray();
    $pa['menu'] = array(
        array(
            'text' => $modx->lexicon('namespace_remove'),
            'handler' => 'this.remove.createDelegate(this,["namespace_remove_confirm"])',
        )
    );
    $ps[] = $pa;
}

return $this->outputArray($ps,$count);
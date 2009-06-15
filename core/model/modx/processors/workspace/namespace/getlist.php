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

if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('permission_denied'));

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

/* build query */
$c = $modx->newQuery('modNamespace');
if (!empty($_REQUEST['name'])) {
    $c->where(array(
        'name:LIKE' => '%'.$_REQUEST['name'].'%',
    ));
}

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
/* get namespaces */
$namespaces = $modx->getCollection('modNamespace',$c);
$count = $modx->getCount('modNamespace');

/* loop through */
$list = array();
foreach ($namespaces as $namespace) {
    $namespaceArray = $namespace->toArray();
    $namespaceArray['menu'] = array(
        array(
            'text' => $modx->lexicon('namespace_remove'),
            'handler' => 'this.remove.createDelegate(this,["namespace_remove_confirm"])',
        )
    );
    $list[] = $namespaceArray;
}

return $this->outputArray($list,$count);
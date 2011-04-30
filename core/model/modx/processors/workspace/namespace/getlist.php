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
if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace','namespace');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$search = $modx->getOption('search',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('modNamespace');
if (!empty($search)) {
    $c->where(array(
        'name:LIKE' => '%'.$search.'%',
        'OR:path:LIKE' => '%'.$search.'%',
    ));
}
$count = $modx->getCount('modNamespace',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

/* get namespaces */
$namespaces = $modx->getCollection('modNamespace',$c);

/* loop through */
$list = array();
foreach ($namespaces as $namespace) {
    $namespaceArray = $namespace->toArray();
    $namespaceArray['perm'] = array();
    $namespaceArray['perm'][] = 'pedit';
    $namespaceArray['perm'][] = 'premove';
    $list[] = $namespaceArray;
}

return $this->outputArray($list,$count);
<?php
/**
 * Grabs a list of contexts.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to key.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.context
 */
if (!$modx->hasPermission('view_context')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('context');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'key');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$search = $modx->getOption('search',$scriptProperties,'');

/* query contexts */
$c = $modx->newQuery('modContext');
if (!empty($search)) {
    $c->where(array(
        'key:LIKE' => '%'.$search.'%',
        'OR:description:LIKE' => '%'.$search.'%',
    ));
}
$count = $modx->getCount('modContext',$c);

$c->sortby($modx->getSelectColumns('modContext','modContext','',array($sort)),$dir);
if ($isLimit) $c->limit($limit,$start);
$contexts = $modx->getCollection('modContext',$c);

$canEdit = $modx->hasPermission('edit_context');
$canRemove = $modx->hasPermission('delete_context');

/* iterate through contexts */
$list = array();
foreach ($contexts as $context) {
    if (!$context->checkPolicy('list')) continue;

    $contextArray = $context->toArray();
    $contextArray['perm'] = array();
    if ($canEdit) {
        $contextArray['perm'][] = 'pedit';
    }
    if (!in_array($context->get('key'),array('mgr','web')) && $canRemove) {
        $contextArray['perm'][] = 'premove';
    }
    $list[]= $contextArray;
}
return $this->outputArray($list,$count);
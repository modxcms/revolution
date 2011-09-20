<?php
/**
 * Gets a list of dashboards
 *
 * @param string $username (optional) Will filter the grid by searching for this
 * username
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 * 
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission('source_view')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('sources');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* query for users */
$c = $modx->newQuery('sources.modMediaSource');
if (!empty($scriptProperties['query'])) {
    $c->where(array('modMediaSource.name:LIKE' => '%'.$scriptProperties['query'].'%'));
    $c->orCondition(array('modMediaSource.description:LIKE' => '%'.$scriptProperties['query'].'%'));
}
$count = $modx->getCount('sources.modMediaSource',$c);
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$sources = $modx->getCollection('sources.modMediaSource',$c);

$canEdit = $modx->hasPermission('source_edit');
$canSave = $modx->hasPermission('source_save');
$canRemove = $modx->hasPermission('source_delete');

/* iterate through users */
$list = array();
/** @var modMediaSource $source */
foreach ($sources as $source) {
    if (!$source->checkPolicy('list')) continue;
    $sourceArray = $source->toArray();

    $cls = array();
    if ($source->checkPolicy('save') && $canSave && $canEdit) $cls[] = 'pupdate';
    if ($source->checkPolicy('remove') && $canRemove) $cls[] = 'premove';
    if ($source->checkPolicy('copy') && $canSave) $cls[] = 'pduplicate';
    $sourceArray['cls'] = implode(' ',$cls);

    $list[] = $sourceArray;
}
return $this->outputArray($list,$count);
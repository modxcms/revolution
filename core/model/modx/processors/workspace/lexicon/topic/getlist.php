<?php
/**
 * Gets a list of lexicon topics
 *
 * @param string $namespace (optional) Filters by this namespace. Defaults to
 * core.
 * @param string $name (optional) If set, will search by this name.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
if (empty($_REQUEST['namespace'])) $_REQUEST['namespace'] = 'core';

/* filter by namespace */
$where = array(
    'namespace' => $_REQUEST['namespace'],
);
/* if set, filter by name */
if (!empty($_REQUEST['name'])) {
	$where['name:LIKE'] = '%'.$_REQUEST['name'].'%';
}

$c = $modx->newQuery('modLexiconTopic');
$c->where($where);
$count = $modx->getCount('modLexiconTopic',$c);

$c->sortby($sort,$dir);
if ($isLimit) { $c->limit($limit,$start); }
$topics = $modx->getCollection('modLexiconTopic',$c);

/* loop through */
$list = array();
foreach ($topics as $topic) {
    $topicArray = $topic->toArray();

    $topicArray['menu'] = array(
        array(
            'text' => $modx->lexicon('topic_remove'),
            'handler' => 'this.remove.createDelegate(this,["topic_remove_confirm"])',
        ),
    );
    $list[] = $topicArray;
}

return $this->outputArray($list,$count);
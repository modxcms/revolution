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
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (empty($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (empty($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if (empty($_REQUEST['namespace'])) $_REQUEST['namespace'] = 'core';

/* filter by namespace */
$wa = array(
    'namespace' => $_REQUEST['namespace'],
);
/* if set, filter by name */
if (!empty($_REQUEST['name'])) {
	$wa['name:LIKE'] = '%'.$_REQUEST['name'].'%';
}

$c = $modx->newQuery('modLexiconTopic');
$c->where($wa);
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) { $c->limit($_REQUEST['limit'],$_REQUEST['start']); }

$topics = $modx->getCollection('modLexiconTopic',$c);
$count = $modx->getCount('modLexiconTopic',$wa);

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
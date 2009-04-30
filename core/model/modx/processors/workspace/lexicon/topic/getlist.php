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

if (isset($_REQUEST['limit'])) $limit = true;
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['namespace'])) $_REQUEST['namespace'] = 'core';

$wa = array(
    'namespace' => $_REQUEST['namespace'],
);

if (isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
	$wa['name:LIKE'] = '%'.$_REQUEST['name'].'%';
}

$c = $modx->newQuery('modLexiconTopic');
$c->where($wa);
$c->sortby('name', 'ASC');
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);
$topics = $modx->getCollection('modLexiconTopic',$c);
$count = $modx->getCount('modLexiconTopic',$wa);

$ps = array();
foreach ($topics as $topic) {
    $pa = $topic->toArray();

    $pa['menu'] = array(
        array(
            'text' => $modx->lexicon('topic_remove'),
            'handler' => 'this.remove.createDelegate(this,["topic_remove_confirm"])',
        ),
    );
    $ps[] = $pa;
}

return $this->outputArray($ps,$count);
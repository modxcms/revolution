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
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
if (empty($scriptProperties['namespace'])) $scriptProperties['namespace'] = 'core';
if (empty($scriptProperties['language'])) $scriptProperties['language'] = 'en';

$topics = $modx->lexicon->getTopicList($scriptProperties['language'],$scriptProperties['namespace']);
$count = count($topics);
if ($isLimit) {
    $topics = array_slice($topics,$start,$limit,true);
}

/* loop through */
$list = array();
foreach ($topics as $topic) {
    $list[] = array(
        'name' => $topic,
    );
}

return $this->outputArray($list,$count);
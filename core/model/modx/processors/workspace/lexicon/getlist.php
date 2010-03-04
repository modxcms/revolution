<?php
/**
 * Gets a list of lexicon entries
 *
 * @param string $namespace (optional) If set, will filter by namespace.
 * Defaults to core.
 * @param integer $topic (optional) If set, will filter by this topic
 * @param string $language (optional) If set, will filter by language. Defaults
 * to en.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
if (empty($scriptProperties['namespace'])) $scriptProperties['namespace'] = 'core';
if (empty($scriptProperties['language'])) $scriptProperties['language'] = 'en';

/* if specifying a topic */
if (empty($scriptProperties['topic'])) {
    $topic = $modx->getObject('modLexiconTopic',array(
        'name' => 'default',
        'namespace' => 'core',
    ));
} else {
    $topic = $modx->getObject('modLexiconTopic',$scriptProperties['topic']);
    if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));
}
$where = array(
    'namespace' => $scriptProperties['namespace'],
    'topic' => $topic->get('id'),
    'language' => $scriptProperties['language'],
);
/* if filtering by name */
if (!empty($scriptProperties['name'])) {
	$where['name:LIKE'] = '%'.$scriptProperties['name'].'%';
}

/* setup query */
$c = $modx->newQuery('modLexiconEntry');
$c->where($where);
$count = $modx->getCount('modLexiconEntry',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($scriptProperties['limit'],$scriptProperties['start']);
$entries = $modx->getCollection('modLexiconEntry',$c);

/* loop through */
$list = array();
foreach ($entries as $entry) {
    $entryArray = $entry->toArray();

    $entryArray['editedon'] = $entry->get('editedon') == '0000-00-00 00:00:00'
                           || $entry->get('editedon') == null
        ? ''
        : strftime('%b %d, %Y %I:%M %p',strtotime($entry->get('editedon')));

    $entryArray['menu'] = array(
        array(
            'text' => $modx->lexicon('entry_update'),
            'handler' => array( 'xtype' => 'modx-window-lexicon-entry-update' ),
        ),
        '-',
        array(
            'text' => $modx->lexicon('entry_remove'),
            'handler' => 'this.remove.createDelegate(this,["entry_remove_confirm"])',
        ),
    );
    $list[] = $entryArray;
}

return $this->outputArray($list,$count);
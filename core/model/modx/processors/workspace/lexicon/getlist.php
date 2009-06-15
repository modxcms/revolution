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
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (empty($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (empty($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if (empty($_REQUEST['namespace'])) $_REQUEST['namespace'] = 'core';
if (empty($_REQUEST['language'])) $_REQUEST['language'] = 'en';

/* if specifying a topic */
if (empty($_REQUEST['topic'])) {
    $topic = $modx->getObject('modLexiconTopic',array(
        'name' => 'default',
        'namespace' => 'core',
    ));
} else {
    $topic = $modx->getObject('modLexiconTopic',$_REQUEST['topic']);
    if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));
}
$wa = array(
    'namespace' => $_REQUEST['namespace'],
    'topic' => $topic->get('id'),
    'language' => $_REQUEST['language'],
);
/* if filtering by name */
if (!empty($_REQUEST['name'])) {
	$wa['name:LIKE'] = '%'.$_REQUEST['name'].'%';
}

/* setup query */
$c = $modx->newQuery('modLexiconEntry');
$c->where($wa);
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}

/* get entries */
$entries = $modx->getCollection('modLexiconEntry',$c);
$count = $modx->getCount('modLexiconEntry',$wa);

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
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

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['namespace'])) $_REQUEST['namespace'] = 'core';
if (!isset($_REQUEST['language'])) $_REQUEST['language'] = 'en';

if (!isset($_POST['topic']) || $_POST['topic'] == '') {
    $topic = $modx->getObject('modLexiconTopic',array(
        'name' => 'default',
        'namespace' => 'core',
    ));
} else {
    $topic = $modx->getObject('modLexiconTopic',$_POST['topic']);
    if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));
}

$wa = array(
    'namespace' => $_REQUEST['namespace'],
    'topic' => $topic->get('id'),
    'language' => $_REQUEST['language'],
);
if (isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
	$wa['name:LIKE'] = '%'.$_REQUEST['name'].'%';
}

$c = $modx->newQuery('modLexiconEntry');
$c->where($wa);
$c->sortby('name', 'ASC');
$c->limit($_REQUEST['limit'],$_REQUEST['start']);

$entries = $modx->getCollection('modLexiconEntry',$c);
$count = $modx->getCount('modLexiconEntry',$wa);


$ps = array();
foreach ($entries as $entry) {
    $pa = $entry->toArray();

    $pa['editedon'] = $entry->get('editedon') == '0000-00-00 00:00:00' || $entry->get('editedon') == null
        ? ''
        : strftime('%b %d, %Y %I:%M %p',$entry->get('editedon'));

    $pa['menu'] = array(
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
    $ps[] = $pa;
}

return $this->outputArray($ps,$count);
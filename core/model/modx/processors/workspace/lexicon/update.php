<?php
/**
 * Updates a lexicon entry
 *
 * @param integer $id The ID of the entry.
 * @param string $name The name of the entry.
 * @param string $language The IANA code for the language.
 * @param string $value The value of the entry.
 * @param integer $topic The topic associated with this entry.
 * @param string $namespace The namespace associated with this entry.
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('entry_err_ns'));
$entry = $modx->getObject('modLexiconEntry',$_POST['id']);
if ($entry == null) {
    return $modx->error->failure(sprintf($modx->lexicon('entry_err_nfs'),$_POST['id']));
}

if (!isset($_POST['topic'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->getObject('modLexiconTopic',$_POST['topic']);
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));


$old_namespace = $entry->get('namespace');
$old_topic = $entry->getOne('modLexiconTopic');

if (!isset($_POST['name']) || $_POST['name'] == '') {
    return $modx->error->failure($modx->lexicon('entry_err_ns_name'));
}

$entry->set('name',$_POST['name']);
$entry->set('value',$_POST['value']);
$entry->set('namespace',$_POST['namespace']);
$entry->set('topic',$topic->get('id'));
$entry->set('language',$_POST['language']);

if (!$entry->save()) return $modx->error->failure($modx->lexicon('entry_err_save'));

$r = $modx->lexicon->clearCache($old_namespace.'/'.$old_topic->get('name').'.cache.php');
$r = $modx->lexicon->clearCache($entry->get('namespace').'/'.$topic->get('name').'.cache.php');

/* log manager action */
$modx->logManagerAction('lexicon_entry_update','modLexiconEntry',$entry->get('id'));

return $modx->error->success();
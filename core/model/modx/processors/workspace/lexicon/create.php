<?php
/**
 * Creates a lexicon entry.
 *
 * @param string $name The name of the entry.
 * @param string $language The IANA code for the language.
 * @param string $value The value of the entry.
 * @param integer $topic The topic associated with this entry.
 * @param string $namespace The namespace associated with this entry.
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.focus
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

if (!isset($_POST['topic'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->getObject('modLexiconTopic',$_POST['topic']);
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));

$entry = $modx->newObject('modLexiconEntry');
$entry->set('name',$_POST['name']);
$entry->set('namespace',$namespace->get('name'));
$entry->set('topic',$topic->get('id'));
$entry->set('language',$_POST['language']);
$entry->set('value',$_POST['value']);
$entry->set('createdon',date('Y-m-d h:i:s'));

if ($entry->save() === false) {
    return $modx->error->failure($modx->lexicon('entry_err_create'));
}

$entry->clearCache();

/* log manager action */
$modx->logManagerAction('lexicon_entry_create','modLexiconEntry',$entry->get('id'));

return $modx->error->success();
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

/* get entry */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('entry_err_ns'));
$entry = $modx->getObject('modLexiconEntry',$scriptProperties['id']);
if ($entry == null) {
    return $modx->error->failure($modx->lexicon('entry_err_nfs',array('key' => $scriptProperties['id'])));
}

/* verify topic if set */
if (!empty($scriptProperties['topic'])) {
    $topic = $modx->getObject('modLexiconTopic',$scriptProperties['topic']);
    if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));
}

$old_namespace = $entry->get('namespace');
$old_topic = $entry->getOne('Topic');

/* validate name */
if (empty($scriptProperties['name'])) {
    return $modx->error->failure($modx->lexicon('entry_err_ns_name'));
}

/* save entry */
$entry->fromArray($scriptProperties);
if ($entry->save() == false) {
    return $modx->error->failure($modx->lexicon('entry_err_save'));
}

/* clear caches for old and new entries */
$r = $modx->lexicon->clearCache($old_namespace.'/'.$old_topic->get('name').'.cache.php');
$r = $modx->lexicon->clearCache($entry->get('namespace').'/'.$topic->get('name').'.cache.php');

/* log manager action */
$modx->logManagerAction('lexicon_entry_update','modLexiconEntry',$entry->get('id'));

return $modx->error->success();
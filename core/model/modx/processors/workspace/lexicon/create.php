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

/* verify namespace */
if (empty($scriptProperties['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$scriptProperties['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

/* verify topic */
if (empty($scriptProperties['topic'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->getObject('modLexiconTopic',$scriptProperties['topic']);
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));

/* validation */
if (empty($scriptProperties['name'])) {
    return $modx->error->failure($modx->lexicon('entry_err_ns_name'));
}

/* create entry */
$entry = $modx->newObject('modLexiconEntry');
$entry->fromArray($scriptProperties);
$entry->set('createdon',date('Y-m-d h:i:s'));

/* save entry */
if ($entry->save() === false) {
    return $modx->error->failure($modx->lexicon('entry_err_create'));
}

/* clear entry cache */
$entry->clearCache();

/* log manager action */
$modx->logManagerAction('lexicon_entry_create','modLexiconEntry',$entry->get('id'));

return $modx->error->success();
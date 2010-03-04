<?php
/**
 * Updates a lexicon topic
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get topic */
if (!isset($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->newObject('modLexiconTopic',$scriptProperties['id']);
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));

/* validate namespace if specified */
if (!empty($scriptProperties['namespace'])) {
    $namespace = $modx->newObject('modNamespace',$scriptProperties['namespace']);
    if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));
}

/* set and save topic */
$topic->fromArray($scriptProperties);
if ($topic->save() === false) {
    return $modx->error->failure($modx->lexicon('topic_err_save'));
}

/* log manager action */
$modx->logManagerAction('lexicon_topic_update','modLexiconTopic',$topic->get('id'));

return $modx->error->success();
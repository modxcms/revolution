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
if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->newObject('modLexiconTopic',$_POST['id']);
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));

/* validate namespace if specified */
if (!empty($_POST['namespace'])) {
    $namespace = $modx->newObject('modNamespace',$_POST['namespace']);
    if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));
}

/* set and save topic */
$topic->fromArray($_POST);
if ($topic->save() === false) {
    return $modx->error->failure($modx->lexicon('topic_err_save'));
}

/* log manager action */
$modx->logManagerAction('lexicon_topic_update','modLexiconTopic',$topic->get('id'));

return $modx->error->success();
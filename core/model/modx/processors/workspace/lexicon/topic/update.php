<?php
/**
 * Updates a lexicon topic
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->newObject('modLexiconTopic',$_POST['id']);
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));

if (!isset($_POST['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->newObject('modNamespace',$_POST['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

$topic->set('namespace',$namespace->get('name'));

if ($topic->save() === false) {
    return $modx->error->failure($modx->lexicon('topic_err_save'));
}

/* log manager action */
$modx->logManagerAction('lexicon_topic_update','modLexiconTopic',$topic->get('id'));

return $modx->error->success();
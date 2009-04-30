<?php
/**
 * Updates a topic from a grid
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->newObject('modLexiconTopic',$_DATA['id']);
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));

if (!isset($_DATA['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->newObject('modNamespace',$_DATA['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

$topic->set('namespace',$namespace->get('name'));

if ($topic->save() === false) {
    return $modx->error->failure($modx->lexicon('topic_err_save'));
}

/* log manager action */
$modx->logManagerAction('lexicon_topic_update','modLexiconTopic',$topic->get('id'));

return $modx->error->success();
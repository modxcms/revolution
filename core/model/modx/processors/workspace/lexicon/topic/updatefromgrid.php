<?php
/**
 * Updates a topic from a grid
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get topic */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->newObject('modLexiconTopic',$_DATA['id']);
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));

/* validate namespace if specified */
if (!empty($_DATA['namespace'])) {
    $namespace = $modx->newObject('modNamespace',$_DATA['namespace']);
    if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));
}

/* set and save topic */
$topic->fromArray($_DATA);
if ($topic->save() === false) {
    return $modx->error->failure($modx->lexicon('topic_err_save'));
}

/* log manager action */
$modx->logManagerAction('lexicon_topic_update','modLexiconTopic',$topic->get('id'));

return $modx->error->success();
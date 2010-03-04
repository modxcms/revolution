<?php
/**
 * Remove a lexicon topic
 *
 * @param integer $id The ID of the topic
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get topic */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->getObject('modLexiconTopic',array(
    'id' => $scriptProperties['id'],
));
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));

/* remove topic */
if ($topic->remove() === false) {
    return $modx->error->failure($modx->lexicon('topic_err_remove'));
}

/* log manager action */
$modx->logManagerAction('lexicon_topic_remove','modLexiconTopic',$topic->get('id'));

return $modx->error->success();
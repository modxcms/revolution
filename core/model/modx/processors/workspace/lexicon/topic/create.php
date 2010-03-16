<?php
/**
 * Creates a lexicon topic
 *
 * @param string $name The name of the topic
 * @param string $namespace The namespace to associate with
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* validate namespace */
if (empty($scriptProperties['namespace'])) $scriptProperties['namespace'] = 'core';
$namespace = $modx->getObject('modNamespace',$scriptProperties['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

/* create topic */
$topic = $modx->newObject('modLexiconTopic');
$topic->fromArray($scriptProperties);

/* save topic */
if ($topic->save() === false) {
	return $modx->error->failure($modx->lexicon('topic_err_create'));
}

/* log manager action */
$modx->logManagerAction('lexicon_topic_create','modLexiconTopic',$topic->get('id'));

return $modx->error->success('',$topic);
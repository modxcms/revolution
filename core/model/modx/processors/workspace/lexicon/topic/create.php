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

if (!isset($_POST['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

$topic = $modx->newObject('modLexiconTopic');
$topic->set('name',$_POST['name']);
$topic->set('namespace',$namespace->get('name'));

if ($topic->save() === false) {
	return $modx->error->failure($modx->lexicon('topic_err_create'));
}

/* log manager action */
$modx->logManagerAction('lexicon_topic_create','modLexiconTopic',$topic->get('id'));

return $modx->error->success();
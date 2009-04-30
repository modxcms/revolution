<?php
/**
 * Create an action
 *
 * @param string $controller The controller location
 * @param boolean $loadheaders Whether or not to load header templates for the
 * action
 * @param string $namespace The namespace for the action
 * @param string $lang_topics The lexicon topics for the action
 * @param string $assets
 * @param integer $parent (optional) The parent for the action. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.system.action
 */
$modx->lexicon->load('action','menu','namespace');

if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['controller']) || $_POST['controller'] == '') {
	return $modx->error->failure($modx->lexicon('controller_err_ns'));
}
$loadheaders = isset($_POST['loadheaders']) ? true : false;

if (!isset($_POST['parent'])) return $modx->error->failure($modx->lexicon('action_parent_err_ns'));
if ($_POST['parent'] == 0) {
	$parent = $modx->newObject('modAction');
	$parent->set('id',0);
} else {
	$parent = $modx->getObject('modAction',$_POST['parent']);
	if ($parent == null) return $modx->error->failure($modx->lexicon('action_parent_err_nf'));
}

if (!isset($_POST['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_nf'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

$action = $modx->newObject('modAction');
$action->set('namespace',$namespace->get('name'));
$action->set('parent',$parent->get('id'));
$action->set('controller',$_POST['controller']);
$action->set('loadheaders',$loadheaders);
$action->set('lang_topics',$_POST['lang_topics']);
$action->set('assets',$_POST['assets']);

if ($action->save() == false) {
    return $modx->error->failure($modx->lexicon('action_err_create'));
}

/* log manager action */
$modx->logManagerAction('action_create','modAction',$action->get('id'));

return $modx->error->success();
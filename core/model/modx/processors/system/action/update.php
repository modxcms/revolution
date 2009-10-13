<?php
/**
 * Updates an action
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
if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu','namespace');


$_POST['haslayout'] = !empty($_POST['haslayout']);

/* get action */
if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('action_err_ns'));
$action = $modx->getObject('modAction',$_POST['id']);
if ($action == null) return $modx->error->failure($modx->lexicon('action_err_nf'));

/* verify controller */
if (empty($_POST['controller'])) return $modx->error->failure($modx->lexicon('controller_err_ns'));

/* verify parent */
if (!isset($_POST['parent'])) return $modx->error->failure($modx->lexicon('action_parent_err_ns'));
if (!empty($_POST['parent'])) {
    $parent = $modx->getObject('modAction',$_POST['parent']);
    if ($parent == null) return $modx->error->failure($modx->lexicon('action_parent_err_nf'));
}

/* verify namespace */
if (empty($_POST['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_nf'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

/* save action */
$action->fromArray($_POST);
if ($action->save() == false) {
    return $modx->error->failure($modx->lexicon('action_err_save'));
}

/* log manager action */
$modx->logManagerAction('action_update','modAction',$action->get('id'));

return $modx->error->success('',$action);
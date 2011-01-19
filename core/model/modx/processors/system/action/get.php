<?php
/**
 * Gets an action
 *
 * @param integer $id The ID of the action
 *
 * @package modx
 * @subpackage processors.system.action
 */
if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu');

/* get action */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('action_err_ns'));
$action = $modx->getObject('modAction',$scriptProperties['id']);
if ($action == null) return $modx->error->failure($modx->lexicon('action_err_nf'));

/* get parent */
$parent = $action->getOne('Parent');
if ($parent != null) {
    $action->set('parent',$parent->get('id'));
    $action->set('parent_controller',$parent->get('controller'));
}

return $modx->error->success('',$action);
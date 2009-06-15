<?php
/**
 * Gets an action
 *
 * @param integer $id The ID of the action
 *
 * @package modx
 * @subpackage processors.system.action
 */
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('action_err_ns'));
$action = $modx->getObject('modAction',$_REQUEST['id']);
if ($action == null) return $modx->error->failure($modx->lexicon('action_err_nf'));

$parent = $action->getOne('Parent');
if ($parent != null) {
    $action->set('parent',$parent->get('id'));
    $action->set('parent_controller',$parent->get('controller'));
}

return $modx->error->success('',$action);
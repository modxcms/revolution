<?php
/**
 * Removes an action
 *
 * @param integer $id The ID of the action
 *
 * @package modx
 * @subpackage processors.system.action
 */
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('action_err_ns'));
$action = $modx->getObject('modAction',$_POST['id']);
if ($action == null) return $modx->error->failure($modx->lexicon('action_err_nf'));

if ($action->remove() == false) {
    return $modx->error->failure($modx->lexicon('action_err_remove'));
}

/* log manager action */
$modx->logManagerAction('action_delete','modAction',$action->get('id'));

return $modx->error->success();
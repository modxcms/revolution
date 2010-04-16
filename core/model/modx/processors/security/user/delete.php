<?php
/**
 * Deletes a user
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission('delete_user')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get user */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$scriptProperties['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_nf'));

/* check if we are deleting our own record */
if ($user->get('id') == $modx->user->get('id')) {
	return $modx->error->failure($modx->lexicon('user_err_cannot_delete_self'));
}

/* invoke OnBeforeUserFormDelete event */
$modx->invokeEvent('OnBeforeUserFormDelete',array(
    'user' => &$user,
    'id' => $user->get('id'),
));

/* remove user */
if ($user->remove() == false) {
    return $modx->error->failure($modx->lexicon('user_err_remove'));
}

/* invoke OnUserFormDelete event */
$modx->invokeEvent('OnUserFormDelete',array(
    'id' => $user->get('id'),
    'user' => &$user,
));

/* log manager action */
$modx->logManagerAction('user_delete','modUser',$user->get('id'));

return $modx->error->success('',$user);
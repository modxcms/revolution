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

/* ensure we cant delete last user in Administrator group */
$group = $modx->getObject('modUserGroup',array('name' => 'Administrator'));
if ($group && $user->isMember('Administrator')) {
    $numberInAdminGroup = $modx->getCount('modUserGroupMember',array(
        'user_group' => $group->get('id'),
    ));
    if ($numberInAdminGroup <= 1) {
        return $modx->error->failure($modx->lexicon('user_err_cannot_delete_last_admin'));
    }
}

/* invoke OnBeforeUserFormDelete event */
$OnBeforeUserFormDelete = $modx->invokeEvent('OnBeforeUserFormDelete',array(
    'user' => &$user,
    'id' => $user->get('id'),
));
$canRemove = $this->processEventResponse($OnBeforeUserFormDelete);
if (!empty($canRemove)) {
    return $modx->error->failure($canSave);
}

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
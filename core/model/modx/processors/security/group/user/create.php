<?php
/**
 * Add a user to a user group
 *
 * @param integer $usergroup The ID of the user group
 * @param integer $user The ID of the user
 * @param integer $role The ID of the role
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get user */
if (empty($scriptProperties['user'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$scriptProperties['user']);
if (!$user) return $modx->error->failure($modx->lexicon('user_err_ns'));

/* get usergroup */
if (empty($scriptProperties['usergroup'])) return $modx->error->failure($modx->lexicon('user_group_err_ns'));
$usergroup = $modx->getObject('modUserGroup',$scriptProperties['usergroup']);
if (!$usergroup) return $modx->error->failure($modx->lexicon('user_group_err_nf'));

/* check role */
if (!empty($scriptProperties['role'])) {
    $role = $modx->getObject('modUserGroupRole',$scriptProperties['role']);
    if (!$role) return $modx->error->failure($modx->lexicon('role_err_nf'));
}

/* check to see if member is already in group */
$alreadyExists = $modx->getObject('modUserGroupMember',array(
	'user_group' => $usergroup->get('id'),
	'member' => $user->get('id'),
));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('user_group_member_err_already_in'));

/* create membership */
$member = $modx->newObject('modUserGroupMember');
$member->set('user_group',$usergroup->get('id'));
$member->set('member',$user->get('id'));
$member->set('role',$scriptProperties['role']);

/* save membership */
if ($member->save() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_save'));
}

return $modx->error->success('',$member);
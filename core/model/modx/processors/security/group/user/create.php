<?php
/**
 * Add a user to a user group
 *
 * @param integer $usergroup The ID of the user group
 * @param integer $user The ID of the user
 * @param integer $role The ID of the role
 *
 * @var modX $modx
 * @var modProcessor $this
 * @var array $scriptProperties
 * 
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get user */
if (empty($scriptProperties['user'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
/** @var modUser $user */
$user = $modx->getObject('modUser',$scriptProperties['user']);
if (!$user) return $modx->error->failure($modx->lexicon('user_err_ns'));

/* get usergroup */
if (empty($scriptProperties['usergroup'])) return $modx->error->failure($modx->lexicon('user_group_err_ns'));
/** @var modUserGroup $usergroup */
$usergroup = $modx->getObject('modUserGroup',$scriptProperties['usergroup']);
if (!$usergroup) return $modx->error->failure($modx->lexicon('user_group_err_nf'));

/* check role */
if (!empty($scriptProperties['role'])) {
    /** @var modUserGroupRole $role */
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
/** @var modUserGroupMember $membership */
$membership = $modx->newObject('modUserGroupMember');
$membership->set('user_group',$usergroup->get('id'));
$membership->set('member',$user->get('id'));
$membership->set('role',$scriptProperties['role']);

$rank = $modx->getCount('modUserGroupMember',array('member' => $user->get('id')));
$membership->set('rank',$rank);

/* save membership */
if ($membership->save() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_save'));
}

return $modx->error->success('',$membership);
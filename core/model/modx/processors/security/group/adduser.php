<?php
/**
 * Add a user to a user group
 *
 * @param integer $user_group The ID of the user group
 * @param intger $member The ID of the user
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get user */
if (!isset($_POST['member'])) return $modx->error->failure($modx->lexicon('user_err_not_specified'));
$user = $modx->getObject('modUser',$_POST['member']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_not_found'));

/* get usergroup */
if (!isset($_POST['user_group'])) return $modx->error->failure($modx->lexicon('user_group_err_not_specified'));
$usergroup = $modx->getObject('modUserGroup',$_POST['user_group']);
if ($usergroup == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));

/* check to see if member is already in group */
$member = $modx->getObject('modUserGroupMember',array(
	'user_group' => $usergroup->get('id'),
	'member' => $user->get('id'),
));
if ($member) return $modx->error->failure($modx->lexicon('user_group_member_err_already_in'));

/* create membership */
$member = $modx->newObject('modUserGroupMember');
$member->set('user_group',$usergroup->get('id'));
$member->set('member',$user->get('id'));

/* save membership */
if ($member->save() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_save'));
}

return $modx->error->success('',$member);
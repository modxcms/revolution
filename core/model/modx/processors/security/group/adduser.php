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
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['user_group'])) return $modx->error->failure($modx->lexicon('user_group_err_not_specified'));
if (!isset($_POST['member'])) return $modx->error->failure($modx->lexicon('user_err_not_specified'));

$user = $modx->getObject('modUser',$_POST['member']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_not_found'));

$group = $modx->getObject('modUserGroup',$_POST['user_group']);
if ($group == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));

$ugu = $modx->getObject('modUserGroupMember',array(
	'user_group' => $group->get('id'),
	'member' => $user->get('id'),
));
if ($ugu != null) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_already_in'));
}

$ugu = $modx->newObject('modUserGroupMember');
$ugu->set('user_group',$_POST['user_group']);
$ugu->set('member',$_POST['member']);

if ($ugu->save() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_save'));
}

return $modx->error->success();
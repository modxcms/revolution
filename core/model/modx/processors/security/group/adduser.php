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
if (empty($scriptProperties['member'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$scriptProperties['member']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_nf'));

/* get usergroup */
if (empty($scriptProperties['user_group'])) return $modx->error->failure($modx->lexicon('user_group_err_ns'));
$usergroup = $modx->getObject('modUserGroup',$scriptProperties['user_group']);
if ($usergroup == null) return $modx->error->failure($modx->lexicon('user_group_err_nf'));

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

/* invoke OnUserBeforeAddToGroup event */
$OnUserBeforeAddToGroup = $modx->invokeEvent('OnUserBeforeAddToGroup',array(
    'user' => &$user,
    'usergroup' => &$usergroup,
));
$canSave = $this->processEventResponse($OnUserBeforeAddToGroup);
if (!empty($canSave)) {
    return $modx->error->failure($canSave);
}

/* save membership */
if ($member->save() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_save'));
}

/* invoke OnUserAddToGroup event */
$modx->invokeEvent('OnUserAddToGroup',array(
    'user' => &$user,
    'usergroup' => &$usergroup,
));

return $modx->error->success('',$member);
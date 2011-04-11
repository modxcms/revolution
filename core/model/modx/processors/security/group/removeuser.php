<?php
/**
 * Remove a user from a user group
 *
 * @param integer $group_id The ID of the group
 * @param integer $user_id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get user */
if (empty($scriptProperties['user_id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$scriptProperties['user_id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_nf'));

/* get usergroup */
if (empty($scriptProperties['group_id'])) return $modx->error->failure($modx->lexicon('user_group_err_ns'));
$usergroup = $modx->getObject('modUserGroup',$scriptProperties['group_id']);
if ($usergroup == null) return $modx->error->failure($modx->lexicon('user_group_err_nf'));

/* get member */
$member = $modx->getObject('modUserGroupMember',array(
	'user_group' => $scriptProperties['group_id'],
	'member' => $scriptProperties['user_id'],
));
if ($member == null) return $modx->error->failure($modx->lexicon('user_group_member_err_not_found'));

/* invoke OnUserBeforeRemoveFromGroup event */
$OnUserBeforeRemoveFromGroup = $modx->invokeEvent('OnUserBeforeRemoveFromGroup',array(
    'user' => &$user,
    'usergroup' => &$usergroup,
));
$canRemove = $this->processEventResponse($OnUserBeforeRemoveFromGroup);
if (!empty($canRemove)) {
    return $modx->error->failure($canRemove);
}

/* remove member */
if ($member->remove() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_remove'));
}

/* invoke OnUserRemoveFromGroup event */
$modx->invokeEvent('OnUserRemoveFromGroup',array(
    'user' => &$user,
    'usergroup' => &$usergroup,
));

return $modx->error->success();
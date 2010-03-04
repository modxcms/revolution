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

/* get member */
$member = $modx->getObject('modUserGroupMember',array(
	'user_group' => $scriptProperties['group_id'],
	'member' => $scriptProperties['user_id'],
));
if ($member == null) return $modx->error->failure($modx->lexicon('user_group_member_err_not_found'));

/* remove member */
if ($member->remove() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_remove'));
}

return $modx->error->success();
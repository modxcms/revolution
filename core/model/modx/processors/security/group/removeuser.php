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
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

$ugu = $modx->getObject('modUserGroupMember',array(
	'user_group' => $_POST['group_id'],
	'member' => $_POST['user_id'],
));
if ($ugu == null) return $modx->error->failure($modx->lexicon('user_group_member_err_not_found'));

if ($ugu->remove() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_remove'));
}

return $modx->error->success();
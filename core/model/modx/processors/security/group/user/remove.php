<?php
/**
 * Remove a user from a user group
 *
 * @param integer $usergroup The ID of the user group
 * @param intger $user The ID of the user
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get user */
if (empty($_POST['user'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
if (empty($_POST['usergroup'])) return $modx->error->failure($modx->lexicon('user_group_err_ns'));
$member = $modx->getObject('modUserGroupMember',array(
    'member' => $_POST['user'],
    'user_group' => $_POST['usergroup'],
));
if (!$member) return $modx->error->failure($modx->lexicon('user_group_member_err_nf'));

/* remove */
if ($member->remove() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_remove'));
}

return $modx->error->success('',$member);
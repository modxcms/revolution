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
if (empty($scriptProperties['user'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
/** @var modUser $user */
$user = $modx->getObject('modUser',$scriptProperties['user']);
if (!$user) return $modx->error->failure($modx->lexicon('user_err_ns'));

/* get usergroup */
if (empty($scriptProperties['usergroup'])) return $modx->error->failure($modx->lexicon('user_group_err_ns'));
/** @var modUserGroup $usergroup */
$usergroup = $modx->getObject('modUserGroup',$scriptProperties['usergroup']);
if (!$usergroup) return $modx->error->failure($modx->lexicon('user_group_err_nf'));

/** @var modUserGroupMember $membership */
$membership = $modx->getObject('modUserGroupMember',array(
    'member' => $user->get('id'),
    'user_group' => $usergroup->get('id'),
));
if (empty($membership)) return $modx->error->failure($modx->lexicon('user_group_member_err_nf'));

/* remove */
if ($membership->remove() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_remove'));
}

/* unset primary group if that was this group */
if ($user->get('primary_group') == $usergroup->get('id')) {
    $user->set('primary_group',0);
    $user->save();
}

return $modx->error->success('',$membership);
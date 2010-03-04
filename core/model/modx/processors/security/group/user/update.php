<?php
/**
 * Update a users role in a user group
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
$member = $modx->getObject('modUserGroupMember',array(
    'member' => $scriptProperties['user'],
    'user_group' => $scriptProperties['usergroup'],
));
if (!$member) return $modx->error->failure($modx->lexicon('user_group_member_err_nf'));

$member->fromArray($scriptProperties);

/* save membership */
if ($member->save() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_save'));
}

return $modx->error->success('',$member);
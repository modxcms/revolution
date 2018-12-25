<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Remove a user from a user group
 *
 * @param integer $group_id The ID of the group
 * @param integer $user_id The ID of the user
 *
 * @var modX $modx
 * @var modProcessor $this
 * @var array $scriptProperties
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('usergroup_user_edit')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get user */
if (empty($scriptProperties['user_id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
/** @var modUser $user */
$user = $modx->getObject('modUser',$scriptProperties['user_id']);
if (empty($user)) return $modx->error->failure($modx->lexicon('user_err_nf'));

/* get usergroup */
if (empty($scriptProperties['group_id'])) return $modx->error->failure($modx->lexicon('user_group_err_ns'));
/** @var modUserGroup $usergroup */
$usergroup = $modx->getObject('modUserGroup',$scriptProperties['group_id']);
if (empty($usergroup)) return $modx->error->failure($modx->lexicon('user_group_err_nf'));

/** @var modUserGroupMember $membership */
$membership = $modx->getObject('modUserGroupMember',array(
	'user_group' => $usergroup->get('id'),
	'member' => $user->get('id'),
));
if (empty($membership)) return $modx->error->failure($modx->lexicon('user_group_member_err_not_found'));

/* invoke OnUserBeforeRemoveFromGroup event */
$OnUserBeforeRemoveFromGroup = $modx->invokeEvent('OnUserBeforeRemoveFromGroup',array(
    'user' => &$user,
    'usergroup' => &$usergroup,
    'membership' => &$membership,
));
$canRemove = $this->processEventResponse($OnUserBeforeRemoveFromGroup);
if (!empty($canRemove)) {
    return $modx->error->failure($canRemove);
}

/* remove member */
if ($membership->remove() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_remove'));
}

/* unset primary group if that was this group */
if ($user->get('primary_group') == $usergroup->get('id')) {
    $user->set('primary_group',0);
    $user->save();
}

/* invoke OnUserRemoveFromGroup event */
$modx->invokeEvent('OnUserRemoveFromGroup',array(
    'user' => &$user,
    'usergroup' => &$usergroup,
    'membership' => &$membership,
));

return $modx->error->success();

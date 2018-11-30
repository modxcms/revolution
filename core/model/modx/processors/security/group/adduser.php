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
 * Add a user to a user group
 *
 * @param integer $user_group The ID of the user group
 * @param integer $member The ID of the user
 *
 * @var modX $modx
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('usergroup_user_edit')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get user */
if (empty($scriptProperties['member'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
/** @var modUser $user */
$user = $modx->getObject('modUser',$scriptProperties['member']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_nf'));

/* get usergroup */
if (empty($scriptProperties['user_group'])) return $modx->error->failure($modx->lexicon('user_group_err_ns'));
/** @var modUserGroup $usergroup */
$usergroup = $modx->getObject('modUserGroup',$scriptProperties['user_group']);
if ($usergroup == null) return $modx->error->failure($modx->lexicon('user_group_err_nf'));

/* check to see if member is already in group */
$member = $modx->getObject('modUserGroupMember',array(
	'user_group' => $usergroup->get('id'),
	'member' => $user->get('id'),
));
if ($member) return $modx->error->failure($modx->lexicon('user_group_member_err_already_in'));

/* create membership */
/** @var modUserGroupMember $membership */
$membership = $modx->newObject('modUserGroupMember');
$membership->set('user_group',$usergroup->get('id'));
$membership->set('member',$user->get('id'));
$rank = $modx->getCount('modUserGroupMember',array('member' => $user->get('id')));
$membership->set('rank',$rank);

/* invoke OnUserBeforeAddToGroup event */
$OnUserBeforeAddToGroup = $modx->invokeEvent('OnUserBeforeAddToGroup',array(
    'user' => &$user,
    'usergroup' => &$usergroup,
    'membership' => &$membership,
));
$canSave = $this->processEventResponse($OnUserBeforeAddToGroup);
if (!empty($canSave)) {
    return $modx->error->failure($canSave);
}

/* save membership */
if ($membership->save() == false) {
    return $modx->error->failure($modx->lexicon('user_group_member_err_save'));
}

/* set as primary group if the only group for user */
if ($rank == 0) {
    $user->set('primary_group',$usergroup->get('id'));
    $user->save();
}


/* invoke OnUserAddToGroup event */
$modx->invokeEvent('OnUserAddToGroup',array(
    'user' => &$user,
    'usergroup' => &$usergroup,
    'membership' => &$membership,
));

return $modx->error->success('',$membership);

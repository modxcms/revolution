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
 * Gets a user group
 *
 * @param integer $id The ID of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('usergroup_view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

if (empty($scriptProperties['id'])) {
    $usergroup = $modx->newObject('modUserGroup');
    $usergroup->set('id',0);
    $usergroup->set('name','('.$modx->lexicon('anonymous').')');
} else {
    $usergroup = $modx->getObject('modUserGroup',$scriptProperties['id']);
    if ($usergroup == null) return $modx->error->failure($modx->lexicon('user_group_err_nf'));
}

if (!empty($scriptProperties['getUsers']) && !empty($scriptProperties['id'])) {
    $c = $modx->newQuery('modUserGroupMember');
    $c->select('
        modUserGroupMember.*,
        User.username AS username,
        UserGroupRole.name AS role_name
    ');
    $c->innerJoin('modUser','User');
    $c->leftJoin('modUserGroupRole','UserGroupRole');
    $c->where(array(
        'user_group' => $usergroup->get('id'),
    ));
    $c->sortby('UserGroupRole.authority','ASC');
    $usergroupMembers = $modx->getCollection('modUserGroupMember',$c);

    $data = array();
    foreach ($usergroupMembers as $member) {
        $roleName = $member->get('role_name');
        $data[] = array(
            $member->get('member'),
            $member->get('username'),
            $member->get('role'),
            empty($roleName) ? '' : $roleName,
        );
    }
    $usergroup->set('users','(' . $modx->toJSON($data) . ')');
}

if (!empty($scriptProperties['getResourceGroups'])) {
    $c = $modx->newQuery('modAccessResourceGroup');
    $c->select('
        modAccessResourceGroup.*,
        Policy.name AS policy_name,
        Target.name AS resource_group_name
    ');
    $c->innerJoin('modAccessPolicy','Policy');
    $c->innerJoin('modResourceGroup','Target');
    $c->where(array(
        'principal_class' => 'modUserGroup',
        'principal' => $usergroup->get('id'),
    ));
    $c->sortby('principal','ASC');
    $rgaccess = $modx->getCollection('modAccessResourceGroup', $c);

    $data = array();
    foreach ($rgaccess as $acl) {
        $objdata= array(
            $acl->get('id'),
            $acl->get('target'),
            $acl->get('resource_group_name'),
            $acl->get('principal_class'),
            $acl->get('principal'),
            $acl->get('name'),
            $acl->get('authority'),
            $acl->get('policy'),
            $acl->get('policy_name'),
            $acl->get('context_key'),
        );
        $data[] = $objdata;
    }
    $usergroup->set('resourcegroups','(' . $modx->toJSON($data) . ')');
}

return $modx->error->success('',$usergroup);

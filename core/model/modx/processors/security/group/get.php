<?php
/**
 * Gets a user group
 *
 * @param integer $id The ID of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_group_err_not_specified'));
$usergroup = $modx->getObject('modUserGroup',$_REQUEST['id']);
if ($usergroup == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));

if (!empty($_REQUEST['getUsers'])) {
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

if (!empty($_REQUEST['getContexts'])) {
    $c = $modx->newQuery('modAccessContext');
    $c->select('
        modAccessContext.*,
        Policy.name AS policy_name
    ');
    $c->innerJoin('modAccessPolicy','Policy');
    $c->where(array(
        'principal_class' => 'modUserGroup',
        'principal' => $usergroup->get('id'),
    ));
    $c->sortby('principal','ASC');
    $ctxaccess = $modx->getCollection('modAccessContext',$c);

    $data = array();
    foreach ($ctxaccess as $contextAccess) {
        $objdata= array(
            $contextAccess->get('id'),
            $contextAccess->get('target'),
            $contextAccess->get('target'),
            $contextAccess->get('principal_class'),
            $contextAccess->get('principal'),
            $contextAccess->get('name'),
            $contextAccess->get('authority'),
            $contextAccess->get('policy'),
            $contextAccess->get('policy_name'),
        );
        $data[] = $objdata;
    }
    $usergroup->set('contexts','(' . $modx->toJSON($data) . ')');
}


if (!empty($_REQUEST['getResourceGroups'])) {
    $c = $modx->newQuery('modAccessResourceGroup');
    $c->select('
        modAccessResourceGroup.*,
        Policy.name AS policy_name,
        Target.name AS resurce_group_name
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
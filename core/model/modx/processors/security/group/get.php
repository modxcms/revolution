<?php
/**
 * Gets a user group
 *
 * @param integer $id The ID of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_group_err_not_specified'));

$ug = $modx->getObject('modUserGroup',$_REQUEST['id']);
if ($ug == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));

if (isset($_REQUEST['getUsers']) && $_REQUEST['getUsers']) {
    $ugms = $modx->getCollection('modUserGroupMember',array(
        'user_group' => $ug->get('id'),
    ));

    $data = array();
    foreach ($ugms as $ugm) {
        $user = $ugm->getOne('User');
        $role = $ugm->getOne('UserGroupRole');
        if ($user) {
            $role_name = $role != null ? $role->get('name') : '';
            $data[] = array(
                $user->get('id'),
                $user->get('username'),
                $ugm->get('role'),
                $role_name,
            );
        }
    }
    $ug->set('users','(' . $modx->toJSON($data) . ')');
}

if (!empty($_REQUEST['getContexts'])) {
    $c = $modx->newQuery('modAccessContext');
    $c->where(array(
        'principal_class' => 'modUserGroup',
        'principal' => $ug->get('id'),
    ));
    $c->sortby('principal','ASC');
    $objectGraph = '{"Target":{},"Policy":{}}';
    $ctxaccess = $modx->getCollectionGraph('modAccessContext', $objectGraph, $c);

    $data = array();
    foreach ($ctxaccess as $contextAccess) {
        $objdata= array(
            $contextAccess->get('id'),
            $contextAccess->get('target'),
            $contextAccess->Target->get('key'),
            $contextAccess->get('principal_class'),
            $contextAccess->get('principal'),
            $contextAccess->get('name'),
            $contextAccess->get('authority'),
            $contextAccess->get('policy'),
            $contextAccess->Policy->get('name'),
        );
        $data[] = $objdata;
    }
    $ug->set('contexts','(' . $modx->toJSON($data) . ')');
}


if (!empty($_REQUEST['getResourceGroups'])) {
    $c = $modx->newQuery('modAccessResourceGroup');
    $c->where(array(
        'principal_class' => 'modUserGroup',
        'principal' => $ug->get('id'),
    ));
    $c->sortby('principal','ASC');
    $objectGraph = '{"Target":{},"Policy":{}}';
    $ctxaccess = $modx->getCollectionGraph('modAccessContext', $objectGraph, $c);

    $data = array();
    foreach ($ctxaccess as $acl) {
        $objdata= array(
            $acl->get('id'),
            $acl->get('target'),
            $acl->Target->get('name'),
            $acl->get('principal_class'),
            $acl->get('principal'),
            $acl->get('name'),
            $acl->get('authority'),
            $acl->get('policy'),
            $acl->Policy->get('name'),
            $acl->get('context_key'),
        );
        $data[] = $objdata;
    }
    $ug->set('resourcegroups','(' . $modx->toJSON($data) . ')');
}

return $modx->error->success('',$ug);
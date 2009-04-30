<?php
/**
 * Get a user
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'edit_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

if (!isset($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_REQUEST['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_not_found'));

$user->profile = $user->getOne('modUserProfile');



if (isset($_REQUEST['getGroups']) && $_REQUEST['getGroups']) {
    $ugms = $modx->getCollection('modUserGroupMember',array(
        'member' => $user->get('id'),
    ));

    $data = array();
    foreach ($ugms as $ugm) {
        $role = $ugm->getOne('modUserGroupRole');
        $usergroup = $ugm->getOne('modUserGroup');
        $role_name = $role != null ? $role->get('name') : '';
        $data[] = array(
            $usergroup->get('id'),
            $usergroup->get('name'),
            $user->get('id'),
            $ugm->get('role'),
            $role_name,
        );
    }
    $user->set('groups','(' . $modx->toJSON($data) . ')');
}


$ua = $user->toArray();
$ua = array_merge($ua,$user->profile->toArray());
$ua['dob'] = $ua['dob'] != '0'
    ? strftime('%m/%d/%Y',$ua['dob'])
    : '';
$ua['blockeduntil'] = $ua['blockeduntil'] != '0'
    ? strftime('%m/%d/%Y %I:%M %p',$ua['blockeduntil'])
    : '';
$ua['blockedafter'] = $ua['blockedafter'] != '0'
    ? strftime('%m/%d/%Y %I:%M %p',$ua['blockedafter'])
    : '';



return $modx->error->success('',$ua);

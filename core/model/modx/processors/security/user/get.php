<?php
/**
 * Get a user
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission(array('access_permissions' => true, 'edit_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('user');

/* get user */
if (!isset($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_REQUEST['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_not_found'));

$user->profile = $user->getOne('Profile');

/* if set, get groups for user */
if (!empty($_REQUEST['getGroups'])) {
    $c = $modx->newQuery('modUserGroupMember');
    $c->select('
        modUserGroupMember.*,
        UserGroupRole.name AS role_name,
        UserGroup.name AS user_group_name
    ');
    $c->innerJoin('modUserGroupRole','UserGroupRole');
    $c->innerJoin('modUserGroup','UserGroup');
    $c->where(array(
        'member' => $user->get('id'),
    ));
    $members = $modx->getCollection('modUserGroupMember',$c);

    $data = array();
    foreach ($members as $member) {
        $roleName = $member->get('role_name');
        $data[] = array(
            $member->get('user_group'),
            $member->get('user_group_name'),
            $member->get('member'),
            $member->get('role'),
            empty($roleName) ? '' : $roleName,
        );
    }
    $user->set('groups','(' . $modx->toJSON($data) . ')');
}

$ua = $user->toArray();
$ua = array_merge($ua,$user->profile->toArray());
$ua['dob'] = !empty($ua['dob']) ? strftime('%m/%d/%Y',$ua['dob']) : '';
$ua['blockeduntil'] = !empty($ua['blockeduntil']) ? strftime('%m/%d/%Y %I:%M %p',$ua['blockeduntil']) : '';
$ua['blockedafter'] = !empty($ua['blockedafter']) ? strftime('%m/%d/%Y %I:%M %p',$ua['blockedafter']) : '';
$ua['lastlogin'] = !empty($ua['lastlogin']) ? strftime('%m/%d/%Y',$ua['lastlogin']) : '';


return $modx->error->success('',$ua);

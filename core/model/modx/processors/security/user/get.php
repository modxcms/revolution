<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */
/**
 * Get a user
 *
 * @param integer $id The ID of the user
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission('view_user')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get user */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
/** @var modUser $user */
$user = $modx->getObject('modUser',$scriptProperties['id']);
if (!$user) return $modx->error->failure($modx->lexicon('user_err_not_found'));

/* if set, get groups for user */
if (!empty($scriptProperties['getGroups'])) {
    $c = $modx->newQuery('modUserGroupMember');
    $c->select($modx->getSelectColumns('modUserGroupMember','modUserGroupMember'));
    $c->select(array(
        'role_name' => 'UserGroupRole.name',
        'user_group_name' => 'UserGroup.name',
    ));
    $c->leftJoin('modUserGroupRole','UserGroupRole');
    $c->innerJoin('modUserGroup','UserGroup');
    $c->where(array(
        'member' => $user->get('id'),
    ));
    $c->sortby('modUserGroupMember.rank','ASC');
    $members = $modx->getCollection('modUserGroupMember',$c);

    $data = array();
    /** @var modUserGroupMember $member */
    foreach ($members as $member) {
        $roleName = $member->get('role_name');
        if ($member->get('role') == 0) { $roleName = $modx->lexicon('none'); }
        $data[] = array(
            $member->get('user_group'),
            $member->get('user_group_name'),
            $member->get('member'),
            $member->get('role'),
            empty($roleName) ? '' : $roleName,
            $user->get('primary_group') == $member->get('user_group') ? true : false,
            $member->get('rank'),
        );
    }
    $user->set('groups','(' . $modx->toJSON($data) . ')');
}

$userArray = $user->toArray();

$profile = $user->getOne('Profile');
if ($profile) {
    $userArray = array_merge($profile->toArray(),$userArray);
}

$userArray['dob'] = !empty($userArray['dob']) ? strftime('%m/%d/%Y',$userArray['dob']) : '';
$userArray['blockeduntil'] = !empty($userArray['blockeduntil']) ? strftime('%Y-%m-%d %H:%M:%S',$userArray['blockeduntil']) : '';
$userArray['blockedafter'] = !empty($userArray['blockedafter']) ? strftime('%Y-%m-%d %H:%M:%S',$userArray['blockedafter']) : '';
$userArray['lastlogin'] = !empty($userArray['lastlogin']) ? strftime('%m/%d/%Y',$userArray['lastlogin']) : '';

unset($userArray['password'],$userArray['cachepwd']);
return $modx->error->success('',$userArray);

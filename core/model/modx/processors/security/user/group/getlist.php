<?php
/**
 * Gets a list of groups for a user
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission('edit_user')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

if (empty($scriptProperties['user'])) return $this->outputArray(array());

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$user = $modx->getOption('user',$scriptProperties,0);

/* get memberships */
$c = $modx->newQuery('modUserGroupMember');
$c->innerJoin('modUserGroupRole','UserGroupRole');
$c->innerJoin('modUserGroup','UserGroup');
$c->innerJoin('modUser','User',array(
    'User.id' => 'modUserGroupMember.member',
    'User.id' => $user,
));
$c->where(array(
    'member' => $user,
));
$count = $modx->getCount('modUserGroupMember',$c);
$c->select($modx->getSelectColumns('modUserGroupMember','modUserGroupMember'));
$c->select(array(
    'rolename' => 'UserGroupRole.name',
    'name' => 'UserGroup.name',
));

$c->sortby('UserGroup.'.$sort,'ASC');
if ($isLimit) $c->limit($limit,$start);
$members = $modx->getCollection('modUserGroupMember',$c);

$list = array();
/** @var modUserGroupMember $member */
foreach ($members as $member) {
    $memberArray = $member->toArray();
    $list[] = $memberArray;
}

return $this->outputArray($list,$count);
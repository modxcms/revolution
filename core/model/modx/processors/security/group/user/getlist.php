<?php
/**
 * Gets a list of users in a usergroup
 *
 * @param boolean $combo (optional) If true, will append a (anonymous) row
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'username');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$usergroup = $modx->getOption('usergroup',$scriptProperties,false);
$username = !empty($scriptProperties['username']) ? $scriptProperties['username'] : '';

/* build query */
$c = $modx->newQuery('modUser');
$c->innerJoin('modUserGroupMember','UserGroupMembers');
$c->innerJoin('modUserGroup','UserGroup','UserGroupMembers.user_group = UserGroup.id');
$c->leftJoin('modUserGroupRole','UserGroupRole','UserGroupMembers.role = UserGroupRole.id');
$c->where(array(
    'UserGroupMembers.user_group' => $usergroup,
));
if (!empty($username)) {
    $c->where(array(
        'modUser.username:LIKE' => '%'.$username.'%',
    ));
}
$count = $modx->getCount('modUser',$c);

$c->select($modx->getSelectColumns('modUser','modUser'));
$c->select(array(
    'usergroup' => 'UserGroup.id',
    'usergroup_name' => 'UserGroup.name',
    'role' => 'UserGroupRole.id',
    'role_name' => 'UserGroupRole.name',
    'authority' => 'UserGroupRole.authority',
));
$c->sortby('authority','ASC');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$users = $modx->getCollection('modUser',$c);

/* iterate */
$list = array();
foreach ($users as $user) {
    $userArray = $user->toArray();
	$list[] = $userArray;
}
return $this->outputArray($list,$count);
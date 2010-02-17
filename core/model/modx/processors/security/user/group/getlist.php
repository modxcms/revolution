<?php
/**
 * Gets a list of groups for a user
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission('edit_user')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

if (empty($_REQUEST['user'])) return $this->outputArray(array());

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

/* get memberships */
$c = $modx->newQuery('modUserGroupMember');
$c->innerJoin('modUserGroupRole','UserGroupRole');
$c->innerJoin('modUserGroup','UserGroup');
$c->where(array(
    'member' => $_REQUEST['user'],
));
$count = $modx->getCount('modUserGroupMember',$c);
$c->select('
    `modUserGroupMember`.*,
    `UserGroupRole`.`name` AS `rolename`,
    `UserGroup`.`name` AS `name`
');

$c->sortby('`UserGroup`.`'.$sort.'`','ASC');
if ($isLimit) $c->limit($limit,$start);
$members = $modx->getCollection('modUserGroupMember',$c);

$list = array();
foreach ($members as $member) {
    $memberArray = $member->toArray();
    $list[] = $memberArray;
}

return $this->outputArray($list,$count);
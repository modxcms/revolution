<?php
/**
 * Gets a list of groups for a user
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission(array('access_permissions' => true, 'edit_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('user');

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

/* get memberships */
$c = $modx->newQuery('modUserGroupMember');
$c->select('modUserGroupMember.*, '
    . 'UserGroupRole.name AS rolename, '
    . 'UserGroup.name AS name');
$c->innerJoin('modUserGroupRole','UserGroupRole');
$c->innerJoin('modUserGroup','UserGroup');
$c->where(array(
    'member' => $_REQUEST['user'],
));
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);
$members = $modx->getCollection('modUserGroupMember',$c);

$list = array();
foreach ($members as $member) {
    $memberArray = $member->toArray();
    $list[] = $memberArray;
}

return $this->outputArray($list);
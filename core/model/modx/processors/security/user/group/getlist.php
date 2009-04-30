<?php
/**
 * Gets a list of groups for a user
 *
 * @package modx
 * @subpackage processors.security.user
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'edit_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';


if (!isset($_REQUEST['user'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_REQUEST['user']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_nf'));


$c = $modx->newQuery('modUserGroupMember');
$c->select('modUserGroupMember.*, '
    . 'modUserGroupRole.name AS rolename, '
    . 'modUserGroup.name AS name');
$c->innerJoin('modUserGroupRole','modUserGroupRole');
$c->innerJoin('modUserGroup','modUserGroup');
$c->where(array(
    'member' => $user->get('id'),
));
$ugms = $modx->getCollection('modUserGroupMember',$c);

$list = array();
foreach ($ugms as $ugm) {
    $ua = $ugm->toArray();

    $list[] = $ua;
}

return $this->outputArray($list);
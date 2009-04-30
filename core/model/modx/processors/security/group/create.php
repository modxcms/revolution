<?php
/**
 * Create a user group
 *
 * @param string $name (optional) The name of the new user group. Defaults to
 * Untitled User Group.
 * @param integer $parent (optional) The ID of the parent user group. Defaults
 * to 0.
 *
 * @package modx
 * @subpackage processors.security.group
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name'])) $_POST['name'] = $modx->lexicon('user_group_untitled');
if (!isset($_POST['parent'])) $_POST['parent'] = 0;

$ug = $modx->getObject('modUserGroup',array('name' => $_POST['name']));
if ($ug != null) return $modx->error->failure($modx->lexicon('user_group_err_already_exists'));

$ug = $modx->newObject('modUserGroup');
$ug->set('name',$_POST['name']);
$ug->set('parent',$_POST['parent']);

/* then add back in ones in form */
$users = $modx->fromJSON($_POST['users']);
$ugms = array();
foreach ($users as $ua) {
    $ugm = $modx->newObject('modUserGroupMember');
    $ugm->set('user_group',$ug->get('id'));
    $ugm->set('member',$ua['id']);
    $ugm->set('role',$ua['role']);
    $ugms[] = $ugm;
}
$ug->addMany($ugms);

if ($ug->save() == false) {
    return $modx->error->failure($modx->lexicon('user_group_err_create'));
}

/* log manager action */
$modx->logManagerAction('new_user_group','modUserGroup',$ug->get('id'));

return $modx->error->success('',$ug);
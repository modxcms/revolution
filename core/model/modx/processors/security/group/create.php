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

/* users */
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


/* contexts */
$contexts = $modx->fromJSON($_POST['contexts']);
foreach ($contexts as $context) {
    $acl = $modx->newObject('modAccessContext');
    $acl->set('target',$context['target']);
    $acl->set('principal',$ug->get('id'));
    $acl->set('principal_class','modUserGroup');
    $acl->set('authority',$context['authority']);
    $acl->set('policy',$context['policy']);
    $acl->save();
}

/* resource groups */
$resourceGroups = $modx->fromJSON($_POST['resource_groups']);
foreach ($resourceGroups as $resourceGroup) {
    $acl = $modx->newObject('modAccessResourceGroup');
    $acl->set('target',$resourceGroup['target']);
    $acl->set('principal',$ug->get('id'));
    $acl->set('principal_class','modUserGroup');
    $acl->set('authority',$resourceGroup['authority']);
    $acl->set('policy',$resourceGroup['policy']);
    $acl->set('context_key',$resourceGroup['context_key']);
    $acl->save();
}


/* log manager action */
$modx->logManagerAction('new_user_group','modUserGroup',$ug->get('id'));

return $modx->error->success('',$ug);
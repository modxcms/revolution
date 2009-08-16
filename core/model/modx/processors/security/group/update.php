<?php
/**
 * Update a user group
 *
 * @param integer $id The ID of the user group
 * @param string $name The new name of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) {
    return $modx->error->failure($modx->lexicon('user_group_err_not_specified'));
}
$ug = $modx->getObject('modUserGroup',$_POST['id']);
if ($ug == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));

$ug->set('name',$_POST['name']);
$ug->set('parent',$_POST['parent']);

/* users */
$ous = $ug->getMany('UserGroupMembers');
foreach ($ous as $ou) { $ou->remove(); }
$users = $modx->fromJSON($_POST['users']);
foreach ($users as $ua) {
    $ugm = $modx->newObject('modUserGroupMember');
    $ugm->set('user_group',$ug->get('id'));
    $ugm->set('member',$ua['id']);
    $ugm->set('role',empty($ua['role']) ? 0 : $ua['role']);

    $ugm->save();
}

/* contexts */
$acls = $modx->getCollection('modAccessContext',array(
    'principal' => $ug->get('id'),
    'principal_class' => 'modUserGroup',
));
foreach ($acls as $acl) { $acl->remove(); }
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
$acls = $modx->getCollection('modAccessResourceGroup',array(
    'principal' => $ug->get('id'),
    'principal_class' => 'modUserGroup',
));
foreach ($acls as $acl) { $acl->remove(); }
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


if ($ug->save() === false) {
    return $modx->error->failure($modx->lexicon('user_group_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_user_group','modUserGroup',$ug->get('id'));

return $modx->error->success();
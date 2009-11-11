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
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get usergroup */
if (empty($_POST['id'])) {
    $usergroup = $modx->newObject('modUserGroup');
    $usergroup->set('id',0);
} else {
    $usergroup = $modx->getObject('modUserGroup',$_POST['id']);
    if ($usergroup == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));
}

/* set fields */
$usergroup->fromArray($_POST);

/* users */
if (isset($_POST['users']) && !empty($_POST['id'])) {
    $ous = $usergroup->getMany('UserGroupMembers');
    foreach ($ous as $ou) { $ou->remove(); }
    $users = $modx->fromJSON($_POST['users']);
    foreach ($users as $user) {
        $member = $modx->newObject('modUserGroupMember');
        $member->set('user_group',$usergroup->get('id'));
        $member->set('member',$user['id']);
        $member->set('role',empty($user['role']) ? 0 : $user['role']);

        $member->save();
    }
}

/* contexts */
if (isset($_POST['contexts'])) {
    $acls = $modx->getCollection('modAccessContext',array(
        'principal' => $usergroup->get('id'),
        'principal_class' => 'modUserGroup',
    ));
    foreach ($acls as $acl) { $acl->remove(); }
    $contexts = $modx->fromJSON($_POST['contexts']);
    foreach ($contexts as $context) {
        $acl = $modx->newObject('modAccessContext');
        $acl->set('target',$context['target']);
        $acl->set('principal',$usergroup->get('id'));
        $acl->set('principal_class','modUserGroup');
        $acl->set('authority',$context['authority']);
        $acl->set('policy',$context['policy']);
        $acl->save();
    }
}

/* resource groups */
if (isset($_POST['resource_groups'])) {
    $acls = $modx->getCollection('modAccessResourceGroup',array(
        'principal' => $usergroup->get('id'),
        'principal_class' => 'modUserGroup',
    ));
    foreach ($acls as $acl) { $acl->remove(); }
    $resourceGroups = $modx->fromJSON($_POST['resource_groups']);
    foreach ($resourceGroups as $resourceGroup) {
        $acl = $modx->newObject('modAccessResourceGroup');
        $acl->set('target',$resourceGroup['target']);
        $acl->set('principal',$usergroup->get('id'));
        $acl->set('principal_class','modUserGroup');
        $acl->set('authority',$resourceGroup['authority']);
        $acl->set('policy',$resourceGroup['policy']);
        $acl->set('context_key',$resourceGroup['context_key']);
        $acl->save();
    }
}

/* save usergroup if not anonymous */
if (!empty($_POST['id'])) {
    if ($usergroup->save() === false) {
        return $modx->error->failure($modx->lexicon('user_group_err_save'));
    }
}

/* log manager action */
$modx->logManagerAction('save_user_group','modUserGroup',$usergroup->get('id'));

return $modx->error->success('',$usergroup);
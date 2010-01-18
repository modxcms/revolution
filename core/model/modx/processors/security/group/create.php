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
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* field validation */
if (empty($_POST['name'])) $_POST['name'] = $modx->lexicon('user_group_untitled');
if (empty($_POST['parent'])) $_POST['parent'] = 0;

/* check to see if group already exists */
$alreadyExists = $modx->getObject('modUserGroup',array('name' => $_POST['name']));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('user_group_err_already_exists'));

/* add group */
$usergroup = $modx->newObject('modUserGroup');
$usergroup->set('name',$_POST['name']);
$usergroup->set('parent',$_POST['parent']);

/* users */
if (isset($_POST['users'])) {
    $users = $modx->fromJSON($_POST['users']);
    $members = array();
    foreach ($users as $userArray) {
        $member = $modx->newObject('modUserGroupMember');
        $member->set('user_group',$usergroup->get('id'));
        $member->set('member',$userArray['id']);
        $member->set('role',$userArray['role']);
        $ugms[] = $member;
    }
    $usergroup->addMany($members);
}

/* save usergroup */
if ($usergroup->save() == false) {
    return $modx->error->failure($modx->lexicon('user_group_err_create'));
}

/* contexts */
if (!empty($_POST['contexts'])) {
    $contexts = $modx->fromJSON($_POST['contexts']);
    foreach ($contexts as $context) {
        $acl = $modx->newObject('modAccessContext');
        $acl->fromArray($context);
        $acl->set('principal',$usergroup->get('id'));
        $acl->set('principal_class','modUserGroup');
        $acl->save();
    }
}

/* resource groups */
if (!empty($_POST['resource_groups'])) {
    $resourceGroups = $modx->fromJSON($_POST['resource_groups']);
    foreach ($resourceGroups as $resourceGroup) {
        $acl = $modx->newObject('modAccessResourceGroup');
        $acl->fromArray($resourceGroup);
        $acl->set('principal',$usergroup->get('id'));
        $acl->set('principal_class','modUserGroup');
        $acl->save();
    }
}

/* log manager action */
$modx->logManagerAction('new_user_group','modUserGroup',$usergroup->get('id'));

/* fire events */
$eventAttributes = array(
    'usergroup' => &$usergroup,
);
switch ($modx->context->get('key')) {
    case 'web':
        $modx->invokeEvent('OnWebCreateGroup',$eventAttributes);
    case 'mgr':
    default:
        $modx->invokeEvent('OnManagerCreateGroup',$eventAttributes);
        break;
}

return $modx->error->success('',$usergroup);
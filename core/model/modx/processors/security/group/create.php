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
if (empty($scriptProperties['name'])) $scriptProperties['name'] = $modx->lexicon('user_group_untitled');
if (empty($scriptProperties['parent'])) $scriptProperties['parent'] = 0;

/* check to see if group already exists */
$alreadyExists = $modx->getObject('modUserGroup',array('name' => $scriptProperties['name']));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('user_group_err_already_exists'));

/* add group */
$usergroup = $modx->newObject('modUserGroup');
$usergroup->set('name',$scriptProperties['name']);
$usergroup->set('parent',$scriptProperties['parent']);

/* users */
if (isset($scriptProperties['users'])) {
    $users = $modx->fromJSON($scriptProperties['users']);
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
if (!empty($scriptProperties['contexts'])) {
    $contexts = $modx->fromJSON($scriptProperties['contexts']);
    foreach ($contexts as $context) {
        $acl = $modx->newObject('modAccessContext');
        $acl->fromArray($context);
        $acl->set('principal',$usergroup->get('id'));
        $acl->set('principal_class','modUserGroup');
        $acl->save();
    }
}

/* resource groups */
if (!empty($scriptProperties['resource_groups'])) {
    $resourceGroups = $modx->fromJSON($scriptProperties['resource_groups']);
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

return $modx->error->success('',$usergroup);
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
if (empty($scriptProperties['id'])) {
    $usergroup = $modx->newObject('modUserGroup');
    $usergroup->set('id',0);
} else {
    $usergroup = $modx->getObject('modUserGroup',$scriptProperties['id']);
    if ($usergroup == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));
}

/* set fields */
$usergroup->fromArray($scriptProperties);

/* invoke OnBeforeUserGroupFormSave event */
$OnUserGroupBeforeFormSave = $modx->invokeEvent('OnUserGroupBeforeFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'usergroup' => &$usergroup,
    'id' => $usergroup->get('id'),
));
$canSave = $this->processEventResponse($OnUserGroupBeforeFormSave);
if (!empty($canSave)) {
    return $modx->error->failure($canSave);
}

/* users */
if (isset($scriptProperties['users']) && !empty($scriptProperties['id'])) {
    $ous = $usergroup->getMany('UserGroupMembers');
    foreach ($ous as $ou) { $ou->remove(); }
    $users = $modx->fromJSON($scriptProperties['users']);
    foreach ($users as $user) {
        $member = $modx->newObject('modUserGroupMember');
        $member->set('user_group',$usergroup->get('id'));
        $member->set('member',$user['id']);
        $member->set('role',empty($user['role']) ? 0 : $user['role']);

        $member->save();
    }
}

/* save usergroup if not anonymous */
if (!empty($scriptProperties['id'])) {
    if ($usergroup->save() === false) {
        return $modx->error->failure($modx->lexicon('user_group_err_save'));
    }
}

/* invoke OnUserGroupFormSave event */
$OnUserGroupFormSave = $modx->invokeEvent('OnUserGroupFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'usergroup' => &$usergroup,
    'id' => $usergroup->get('id'),
));

/* log manager action */
$modx->logManagerAction('save_user_group','modUserGroup',$usergroup->get('id'));

return $modx->error->success('',$usergroup);
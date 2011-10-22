<?php
/**
 * Update a user.
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission('save_user')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get user */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
/** @var modUser $user */
$user = $modx->getObject('modUser',$scriptProperties['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_nf'));

/* validate post */
$scriptProperties['blocked'] = empty($scriptProperties['blocked']) ? 0 : 1;
$scriptProperties['active'] = empty($scriptProperties['active']) ? 0 : 1;

$newPassword= false;
$result = include $modx->getOption('processors_path').'security/user/_validation.php';
if ($result !== true) return $result;

/* set fields */
$user->fromArray($scriptProperties);
if (!empty($scriptProperties['remote_data'])) {
    $data = $modx->fromJSON($scriptProperties['remote_data']);
    $user->set('remote_data',$data);
}

/* invoke OnBeforeUserFormSave event */
$OnBeforeUserFormSave = $modx->invokeEvent('OnBeforeUserFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'user' => &$user,
    'id' => $user->get('id'),
));
$canSave = $this->processEventResponse($OnBeforeUserFormSave);
if (!empty($canSave)) {
    return $modx->error->failure($canSave);
}

/* save user groups */
if (isset($scriptProperties['groups'])) {
    $primaryGroupId = 0;
    /* remove prior user group links */
    $ugms = $user->getMany('UserGroupMembers');
    /** @var modUserGroupMember $ugm */
    foreach ($ugms as $ugm) { $ugm->remove(); }

    /* create user group links */
    $ugms = array();
    $groups = $modx->fromJSON($scriptProperties['groups']);
    foreach ($groups as $group) {
        $ugm = $modx->newObject('modUserGroupMember');
        $ugm->set('user_group',$group['usergroup']);
        $ugm->set('role',$group['role']);
        $ugm->set('member',$user->get('id'));
        $ugm->set('rank',$group['rank']);
        if (empty($group['rank'])) {
            $primaryGroupId = $group['usergroup'];
        }
        $ugms[] = $ugm;
    }
    $user->addMany($ugms,'UserGroupMembers');
    $user->set('primary_group',$primaryGroupId);
}

/* update user */
if ($user->save() == false) {
    return $modx->error->failure($modx->lexicon('user_err_save'));
}

$user->getOne('Profile');
if (empty($user->Profile)) {
    $user->Profile = $modx->newObject('modUserProfile');
    $user->Profile->set('internalKey',$user->get('id'));
}
$user->Profile->fromArray($scriptProperties);

if ($user->Profile->save() == false) {
    return $modx->error->failure($modx->lexicon('user_profile_err_save'));
}

/* send notification email */
if ($scriptProperties['passwordnotifymethod'] == 'e') {
    $message = $modx->getOption('signupemail_message');
    $placeholders = array(
        'uid' => $user->Profile->get('username'),
        'pwd' => $newPassword,
        'ufn' => $user->Profile->get('fullname'),
        'sname' => $modx->getOption('site_name'),
        'saddr' => $modx->getOption('emailsender'),
        'semail' => $modx->getOption('emailsender'),
        'surl' => $modx->getOption('url_scheme') . $modx->getOption('http_host') . $modx->getOption('manager_url'),
    );
    foreach ($placeholders as $k => $v) {
        $message = str_replace('[[+'.$k.']]',$v,$message);
    }
    $user->sendEmail($message);
}

/* invoke OnUserFormSave event */
$modx->invokeEvent('OnUserFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'user' => &$user,
    'id' => $user->get('id'),
));

/* log manager action */
$modx->logManagerAction('user_update','modUser',$user->get('id'));

if ($newPassword && $scriptProperties['passwordnotifymethod'] == 's') {
    return $modx->error->success($modx->lexicon('user_updated_password_message',array(
        'password' => $newPassword,
    )),$user);
} else {
    return $modx->error->success('',$user);
}
<?php
/**
 * Create a user
 *
 * @param string $newusername The username for the user
 * @param string $passwordnotifymethod The notification method for the user.
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission(array('access_permissions' => true, 'new_user' => true))) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

$user = $modx->newObject('modUser');

/* validate post */
$blocked = empty($scriptProperties['blocked']) ? false : true;

$newPassword= '';
$result = include_once $modx->getOption('processors_path').'security/user/_validation.php';
if ($result !== true) return $result;


/* invoke OnBeforeUserFormSave event */
$modx->invokeEvent('OnBeforeUserFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => $scriptProperties['id'],
    'user' => &$user,
));

/* create user group links */
if (isset($scriptProperties['groups'])) {
    $ugms = array();
    $groups = $modx->fromJSON($scriptProperties['groups']);
    foreach ($groups as $group) {
        $ugm = $modx->newObject('modUserGroupMember');
        $ugm->set('user_group',$group['usergroup']);
        $ugm->set('role',$group['role']);
        $ugms[] = $ugm;
    }
    $user->addMany($ugms,'UserGroupMembers');
}

/* update user */
if ($user->save() == false) {
    return $modx->error->failure($modx->lexicon('user_err_save'));
}

/* create user profile */
$user->profile = $modx->newObject('modUserProfile');
$user->profile->fromArray($scriptProperties);
$user->profile->set('internalKey',$user->get('id'));
$user->profile->set('blocked',$blocked);
$user->profile->set('photo','');

if ($user->profile->save() == false) {
    $user->remove();
    return $modx->error->failure($modx->lexicon('user_err_save_attributes'));
}

/* send notification email */
if ($scriptProperties['passwordnotifymethod'] == 'e') {
    $message = $modx->getOption('signupemail_message');
    $placeholders = array(
        'uid' => $user->get('username'),
        'pwd' => $newPassword,
        'ufn' => $user->profile->get('fullname'),
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
    'mode' => modSystemEvent::MODE_NEW,
    'user' => &$user,
    'id' => $user->get('id'),
));

/* log manager action */
$modx->logManagerAction('user_create','modUser',$user->get('id'));

if (!empty($scriptProperties['passwordnotifymethod']) && $scriptProperties['passwordnotifymethod'] == 's') {
    return $modx->error->success($modx->lexicon('user_created_password_message',array(
        'password' => $newPassword,
    )),$user);
} else {
    return $modx->error->success('',$user);
}
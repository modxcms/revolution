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
$user = $modx->getObject('modUser',$scriptProperties['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_nf'));

/* validate post */
$scriptProperties['blocked'] = empty($scriptProperties['blocked']) ? 0 : 1;
$scriptProperties['active'] = empty($scriptProperties['active']) ? 0 : 1;

$newPassword= false;
$result = include_once $modx->getOption('processors_path').'security/user/_validation.php';
if ($result !== true) return $result;

if ($scriptProperties['passwordnotifymethod'] == 'e') {
	sendMailMessage($scriptProperties['email'], $scriptProperties['username'],$newPassword,$scriptProperties['fullname']);
}

/* invoke OnBeforeUserFormSave event */
$modx->invokeEvent('OnBeforeUserFormSave',array(
	'mode' => 'upd',
    'user' => &$user,
	'id' => $user->get('id'),
));


if (isset($scriptProperties['groups'])) {
    /* remove prior user group links */
    $ugms = $user->getMany('UserGroupMembers');
    foreach ($ugms as $ugm) { $ugm->remove(); }

    /* create user group links */
    $ugms = array();
    $groups = $modx->fromJSON($scriptProperties['groups']);
    foreach ($groups as $group) {
        $ugm = $modx->newObject('modUserGroupMember');
        $ugm->set('user_group',$group['usergroup']);
        $ugm->set('role',$group['role']);
        $ugm->set('member',$user->get('id'));
        $ugms[] = $ugm;
    }
    $user->addMany($ugms,'UserGroupMembers');
}

$user->fromArray($scriptProperties);

/* update user */
if ($user->save() == false) {
    return $modx->error->failure($modx->lexicon('user_err_save'));
}

$user->profile = $user->getOne('Profile');
$user->profile->fromArray($scriptProperties);

if ($user->profile->save() == false) {
	return $modx->error->failure($modx->lexicon('user_profile_err_save'));
}


/* invoke OnManagerSaveUser event */
$modx->invokeEvent('OnManagerSaveUser',array(
    'mode' => 'upd',
    'user' => &$user,
    'userid' => $scriptProperties['id'],
    'username' => $scriptProperties['newusername'],
    'userpassword' => $scriptProperties['newpassword'],
    'useremail' => $scriptProperties['email'],
    'userfullname' => $scriptProperties['fullname'],
    'userroleid' => $scriptProperties['role'],
    'oldusername' => (($scriptProperties['oldusername'] != $scriptProperties['newusername']) ? $scriptProperties['oldusername'] : ''),
    'olduseremail' => (($scriptProperties['oldemail'] != $scriptProperties['email']) ? $scriptProperties['oldemail'] : '')
));

/* invoke OnUserFormSave event */
$modx->invokeEvent('OnUserFormSave',array(
	'mode' => 'upd',
    'user' => &$user,
	'id' => $user->get('id'),
));

/* invoke OnUpdateUser event */
$modx->invokeEvent('OnUpdateUser',array(
    'mode' => 'upd',
    'user' => &$user,
    'id' => $user->get('id'),
));

/* converts date format dd-mm-yyyy to php date */
function convertDate($date) {
	if ($date == '')
		return false;
	list ($d, $m, $Y, $H, $M, $S) = sscanf($date, "%2d-%2d-%4d %2d:%2d:%2d");
	if (!$H && !$M && !$S) {
		return strtotime("$m/$d/$Y");
    } else {
		return strtotime("$m/$d/$Y $H:$M:$S");
    }
}


/* send an email to the user */
function sendMailMessage($email, $uid, $pwd, $ufn) {
	global $modx;

	$message = $modx->getOption('signupemail_message');
	/* replace placeholders */
	$message = str_replace("[[+uid]]", $uid, $message);
	$message = str_replace("[[+pwd]]", $pwd, $message);
	$message = str_replace("[[+ufn]]", $ufn, $message);
	$message = str_replace("[[+sname]]",$modx->getOption('site_name'), $message);
	$message = str_replace("[[+saddr]]", $modx->getOption('emailsender'), $message);
	$message = str_replace("[[+semail]]", $modx->getOption('emailsender'), $message);
	$message = str_replace("[[+surl]]", $modx->getOption('url_scheme') . $modx->getOption('http_host') . $modx->getOption('manager_url'), $message);

    $modx->getService('mail', 'mail.modPHPMailer');
    $modx->mail->set(modMail::MAIL_BODY, $message);
    $modx->mail->set(modMail::MAIL_FROM, $modx->getOption('emailsender'));
    $modx->mail->set(modMail::MAIL_FROM_NAME, $modx->getOption('site_name'));
    $modx->mail->set(modMail::MAIL_SENDER, $modx->getOption('emailsender'));
    $modx->mail->set(modMail::MAIL_SUBJECT, $modx->getOption('emailsubject'));
    $modx->mail->address('to', $email, $ufn);
    $modx->mail->address('reply-to', $modx->getOption('emailsender'));
    $modx->mail->setHTML(true);
    if ($modx->mail->send() == false) {
        return $modx->error->failure($modx->lexicon('error_sending_email_to').$email);
    }
    $modx->mail->reset();
}

/* log manager action */
$modx->logManagerAction('user_update','modUser',$user->get('id'));

if ($newPassword && $scriptProperties['passwordnotifymethod'] == 's') {
	return $modx->error->success($modx->lexicon('user_updated_password_message',array(
        'password' => $newPassword,
    )),$user);
} else {
	return $modx->error->success('',$user);
}
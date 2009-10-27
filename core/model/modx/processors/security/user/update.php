<?php
/**
 * Update a user.
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission(array('access_permissions' => true, 'save_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('user');

/* get user */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_POST['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_not_found'));

/* validate post */
$_POST['blocked'] = empty($_POST['blocked']) ? 0 : 1;
$_POST['active'] = empty($_POST['active']) ? 0 : 1;

$newPassword= false;
include_once $modx->getOption('processors_path').'security/user/_validation.php';
if ($_POST['passwordnotifymethod'] == 'e') {
	sendMailMessage($_POST['email'], $_POST['username'],$newPassword,$_POST['fullname']);
}

/* invoke OnBeforeUserFormSave event */
$modx->invokeEvent('OnBeforeUserFormSave',array(
	'mode' => 'upd',
    'user' => &$user,
	'id' => $_POST['id'],
));


/* remove prior user group links */
$ugms = $user->getMany('UserGroupMembers');
foreach ($ugms as $ugm) { $ugm->remove(); }

/* create user group links */
$ugms = array();
$groups = $modx->fromJSON($_POST['groups']);
foreach ($groups as $group) {
    $ugm = $modx->newObject('modUserGroupMember');
    $ugm->set('user_group',$group['usergroup']);
    $ugm->set('role',$group['role']);
    $ugm->set('member',$user->get('id'));
    $ugms[] = $ugm;
}
$user->addMany($ugms,'UserGroupMembers');

$user->fromArray($_POST);

/* update user */
if ($user->save() == false) {
    return $modx->error->failure($modx->lexicon('user_err_save'));
}

$user->profile = $user->getOne('Profile');
$user->profile->fromArray($_POST);

if ($user->profile->save() == false) {
	return $modx->error->failure($modx->lexicon('user_profile_err_save'));
}


/* invoke OnManagerSaveUser event */
$modx->invokeEvent('OnManagerSaveUser',array(
	'mode' => 'upd',
    'user' => &$user,
	'userid' => $_POST['id'],
	'username' => $_POST['newusername'],
	'userpassword' => $_POST['newpassword'],
	'useremail' => $_POST['email'],
	'userfullname' => $_POST['fullname'],
	'userroleid' => $_POST['role'],
	'oldusername' => (($_POST['oldusername'] != $_POST['newusername']) ? $_POST['oldusername'] : ''),
	'olduseremail' => (($_POST['oldemail'] != $_POST['email']) ? $_POST['oldemail'] : '')
));

/* invoke OnUserFormSave event */
$modx->invokeEvent('OnUserFormSave',array(
	'mode' => 'upd',
    'user' => &$user,
	'id' => $user->get('id'),
));

/* invoke OnUserUpdate event */
$modx->invokeEvent('OnUserUpdate',array(
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

if ($newPassword && $_POST['passwordnotifymethod'] == 's') {
	return $modx->error->success($modx->lexicon('user_updated_password_message',array(
        'password' => $newPassword,
    )),$user);
} else {
	return $modx->error->success('',$user);
}
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
$blocked = empty($_POST['blocked']) ? false : true;

$newPassword= '';
$s = include_once $modx->getOption('processors_path').'security/user/_validation.php';


/* invoke OnBeforeUserFormSave event */
$modx->invokeEvent('OnBeforeUserFormSave',array(
	'mode' => 'new',
	'id' => $_POST['id'],
    'user' => &$user,
));

/* create user group links */
$ugms = array();
$groups = $modx->fromJSON($_POST['groups']);
foreach ($groups as $group) {
    $ugm = $modx->newObject('modUserGroupMember');
    $ugm->set('user_group',$group['usergroup']);
    $ugm->set('role',$group['role']);
    $ugms[] = $ugm;
}
$user->addMany($ugms,'UserGroupMembers');

/* update user */
if ($user->save() == false) {
    return $modx->error->failure($modx->lexicon('user_err_save'));
}

/* create user profile */
$user->profile = $modx->newObject('modUserProfile');
$user->profile->fromArray($_POST);
$user->profile->set('internalKey',$user->get('id'));
$user->profile->set('blocked',$blocked);
$user->profile->set('photo','');

if ($user->profile->save() == false) {
    $user->remove();
	return $modx->error->failure($modx->lexicon('user_err_save_attributes'));
}

/* send email */
if ($_POST['passwordnotifymethod'] == 'e') {
    sendMailMessage($_POST['email'], $_POST['username'],$newPassword,$_POST['fullname']);
}


/* invoke OnManagerSaveUser event */
$modx->invokeEvent('OnManagerSaveUser',array(
	'mode' => 'new',
    'user' => &$user,
	'userid' => $user->get('id'),
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
	'mode' => 'new',
    'user' => &$user,
	'id' => $user->get('id'),
));

/* invoke OnUserCreate event */
$modx->invokeEvent('OnUserCreate',array(
    'mode' => 'new',
    'user' => &$user,
    'id' => $user->get('id'),
));

/* converts date format dd-mm-yyyy to php date */
function convertDate($date) {
	if ($date == '')
		return false;
	list ($d, $m, $Y, $H, $M, $S) = sscanf($date, "%2d-%2d-%4d %2d:%2d:%2d");
	if (!$H && !$M && !$S)
		return strtotime("$m/$d/$Y");
	else
		return strtotime("$m/$d/$Y $H:$M:$S");
}

/* Send an email to the user */
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
    if (!$modx->mail->send()) {
        return $modx->error->failure($modx->lexicon('error_sending_email_to').$email);
        exit;
    }
    $modx->mail->reset();
}

/* log manager action */
$modx->logManagerAction('user_create','modUser',$user->get('id'));

if ($_POST['passwordnotifymethod'] == 's') {
	return $modx->error->success($modx->lexicon('user_created_password_message',array(
        'password' => $newPassword,
    )),$user);
} else {
	return $modx->error->success('',$user);
}
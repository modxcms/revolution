<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */
/* BEGIN VALIDATION */

/* username */
if (empty($_POST['username'])) {
    $modx->error->addField('username',$modx->lexicon('user_err_not_specified_username'));

} else if (!empty($_POST['username']) && $_POST['username'] != $user->get('username')) {
	$alreadyExists = $modx->getObject('modUser',array('username' => $_POST['username']));
	if ($alreadyExists) $modx->error->addField('username',$modx->lexicon('user_err_already_exists'));
	$user->set('username',$_POST['username']);
}


/* password */
if (isset($_POST['newpassword']) && $_POST['newpassword'] != 'false' || empty($_POST['id'])) {
	if (!isset($_POST['passwordnotifymethod'])) {
		$modx->error->addField('password_notify_method',$modx->lexicon('user_err_not_specified_notification_method'));
	}
	if ($_POST['passwordgenmethod'] == 'g') {
        $len = $modx->getOption('password_generated_length',null,8);
		$autoPassword = generate_password($len);
		$user->set('password', $user->encode($autoPassword));
		$newPassword= $autoPassword;
	} else {
		if (empty($_POST['specifiedpassword'])) {
			$modx->error->addField('password',$modx->lexicon('user_err_not_specified_password'));
		} elseif ($_POST['specifiedpassword'] != $_POST['confirmpassword']) {
			$modx->error->addField('password',$modx->lexicon('user_err_password_no_match'));
		} elseif (strlen($_POST['specifiedpassword']) < $modx->getOption('password_min_length',null,6)) {
			$modx->error->addField('password',$modx->lexicon('user_err_password_too_short'));
		} else {
			$user->set('password',$user->encode($_POST['specifiedpassword']));
			$newPassword= $_POST['specifiedpassword'];
		}
	}
}

/* email */
if (empty($_POST['email'])) $modx->error->addField('email',$modx->lexicon('user_err_not_specified_email'));

/* check if the email address already exists */
if (!$modx->getOption('allow_multiple_users_per_email',null,true)) {
    $emailExists = $modx->getObject('modUserProfile',array('email' => $_POST['email']));
    if ($emailExists) {
    	if ($emailExists->get('internalKey') != $_POST['id']) {
    		$modx->error->addField('email',$modx->lexicon('user_err_already_exists_email'));
        }
    }
}

/* phone number */
if (!empty($_POST['phone'])) {
	$_POST['phone'] = str_replace(' ','',$_POST['phone']);
	$_POST['phone'] = str_replace('-','',$_POST['phone']);
	$_POST['phone'] = str_replace('(','',$_POST['phone']);
	$_POST['phone'] = str_replace(')','',$_POST['phone']);
	$_POST['phone'] = str_replace('+','',$_POST['phone']);
	if ((strlen($_POST['phone']) < 10) || (strlen($_POST['phone']) > 11)) {
		/* phone number is either too big or too small */
		$modx->error->addField('phone',$modx->lexicon('user_err_not_specified_phonenumber'));
	}
}

/* mobilephone number */
if (!empty($_POST['mobilephone'])) {
	$_POST['mobilephone'] = str_replace(' ','',$_POST['mobilephone']);
	$_POST['mobilephone'] = str_replace('-','',$_POST['mobilephone']);
	$_POST['mobilephone'] = str_replace('(','',$_POST['mobilephone']);
	$_POST['mobilephone'] = str_replace(')','',$_POST['mobilephone']);
	$_POST['mobilephone'] = str_replace('+','',$_POST['mobilephone']);
	if ((strlen($_POST['mobilephone']) < 10) || (strlen($_POST['mobilephone']) > 11)) {
		/* phone number is either too big or too small */
		$modx->error->addField('mobilephone',$modx->lexicon('user_err_not_specified_mobnumber'));
	}
}

/* birthdate */
if (!empty($_POST['dob'])) {
	$_POST['dob'] = str_replace('-','/',$_POST['dob']);
	if (!$_POST['dob'] = strtotime($_POST['dob']))
		$modx->error->addField('dob',$modx->lexicon('user_err_not_specified_dob'));
}


/* blocked until */
if (!empty($_POST['blockeduntil'])) {
	$_POST['blockeduntil'] = str_replace('-','/',$_POST['blockeduntil']);
	if (!$_POST['blockeduntil'] = strtotime($_POST['blockeduntil']))
		$modx->error->addField('blockeduntil',$modx->lexicon('user_err_not_specified_blockeduntil'));
}

/* blocked after */
if (!empty($_POST['blockedafter'])) {
	$_POST['blockedafter'] = str_replace('-','/',$_POST['blockedafter']);
	if (!$_POST['blockedafter'] = strtotime($_POST['blockedafter']))
		$modx->error->addField('blockedafter',$modx->lexicon('user_err_not_specified_blockedafter'));
}


/* get fields for better error displaying */
$fs = $modx->error->getFields();
$fields = '<ul>';
if (!empty($fs)) {
    foreach ($fs as $f) {
    	$fields .= '<li>'.ucwords(str_replace('_',' ',$f)).'</li>';
    }
}
$fields .= '</ul>';

if ($modx->error->hasError() && !empty($fs)) {
	return $modx->error->failure($fields);
}

/* END VALIDATION */

return true;

/* Generate password */
function generate_password($length = 10) {
	$allowable_characters = 'abcdefghjkmnpqrstuvxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
	$ps_len = strlen($allowable_characters);
	mt_srand((double) microtime() * 1000000);
	$pass = '';
	for ($i = 0; $i < $length; $i++) {
		$pass .= $allowable_characters[mt_rand(0, $ps_len -1)];
	}
	return $pass;
}
<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */
/* BEGIN VALIDATION */

/* username */
if (empty($scriptProperties['username'])) {
    $modx->error->addField('username',$modx->lexicon('user_err_not_specified_username'));
} elseif (!preg_match('/^[^\'\\x3c\\x3e\\(\\);\\x22]+$/', $scriptProperties['username'])) {
    $modx->error->addField('username',$modx->lexicon('user_err_username_invalid'));
} else if (!empty($scriptProperties['username']) && $scriptProperties['username'] != $user->get('username')) {
    $alreadyExists = $modx->getObject('modUser',array('username' => $scriptProperties['username']));
    if ($alreadyExists) $modx->error->addField('username',$modx->lexicon('user_err_already_exists'));
    $user->set('username',$scriptProperties['username']);
}


/* password */
if (isset($scriptProperties['newpassword']) && $scriptProperties['newpassword'] != 'false' || empty($scriptProperties['id'])) {
    if (!isset($scriptProperties['passwordnotifymethod'])) {
        $modx->error->addField('password_notify_method',$modx->lexicon('user_err_not_specified_notification_method'));
    }
    if ($scriptProperties['passwordgenmethod'] == 'g') {
        $len = $modx->getOption('password_generated_length',null,8);
        $autoPassword = generate_password($len);
        $user->set('password', $autoPassword);
        $newPassword= $autoPassword;
    } else {
        if (empty($scriptProperties['specifiedpassword'])) {
            $modx->error->addField('password',$modx->lexicon('user_err_not_specified_password'));
        } elseif ($scriptProperties['specifiedpassword'] != $scriptProperties['confirmpassword']) {
            $modx->error->addField('password',$modx->lexicon('user_err_password_no_match'));
        } elseif (strlen($scriptProperties['specifiedpassword']) < $modx->getOption('password_min_length',null,6)) {
            $modx->error->addField('password',$modx->lexicon('user_err_password_too_short'));
        } elseif (!preg_match('/^[^\'\\x3c\\x3e\\(\\);\\x22]+$/', $scriptProperties['specifiedpassword'])) {
            $modx->error->addField('password', $modx->lexicon('user_err_password_invalid'));
        } else {
            $user->set('password', $scriptProperties['specifiedpassword']);
            $newPassword= $scriptProperties['specifiedpassword'];
        }
    }
}

/* email */
if (empty($scriptProperties['email'])) $modx->error->addField('email',$modx->lexicon('user_err_not_specified_email'));

/* check if the email address already exists */
if (!$modx->getOption('allow_multiple_users_per_email',null,true)) {
    $emailExists = $modx->getObject('modUserProfile',array('email' => $scriptProperties['email']));
    if ($emailExists) {
    	if ($emailExists->get('internalKey') != $scriptProperties['id']) {
            $modx->error->addField('email',$modx->lexicon('user_err_already_exists_email'));
        }
    }
}

/* phone number */
if (!empty($scriptProperties['phone'])) {
    if ($modx->getOption('clean_phone_number',$scriptProperties,false)) {
	$scriptProperties['phone'] = str_replace(' ','',$scriptProperties['phone']);
	$scriptProperties['phone'] = str_replace('-','',$scriptProperties['phone']);
	$scriptProperties['phone'] = str_replace('(','',$scriptProperties['phone']);
	$scriptProperties['phone'] = str_replace(')','',$scriptProperties['phone']);
	$scriptProperties['phone'] = str_replace('+','',$scriptProperties['phone']);
    }
}

/* mobilephone number */
if (!empty($scriptProperties['mobilephone'])) {
    if ($modx->getOption('clean_phone_number',$scriptProperties,false)) {
        $scriptProperties['mobilephone'] = str_replace(' ','',$scriptProperties['mobilephone']);
        $scriptProperties['mobilephone'] = str_replace('-','',$scriptProperties['mobilephone']);
        $scriptProperties['mobilephone'] = str_replace('(','',$scriptProperties['mobilephone']);
        $scriptProperties['mobilephone'] = str_replace(')','',$scriptProperties['mobilephone']);
        $scriptProperties['mobilephone'] = str_replace('+','',$scriptProperties['mobilephone']);
    }
}

/* birthdate */
if (!empty($scriptProperties['dob'])) {
    $scriptProperties['dob'] = str_replace('-','/',$scriptProperties['dob']);
    if (!$scriptProperties['dob'] = strtotime($scriptProperties['dob']))
            $modx->error->addField('dob',$modx->lexicon('user_err_not_specified_dob'));
}


/* blocked until */
if (!empty($scriptProperties['blockeduntil'])) {
    $scriptProperties['blockeduntil'] = str_replace('-','/',$scriptProperties['blockeduntil']);
    if (!$scriptProperties['blockeduntil'] = strtotime($scriptProperties['blockeduntil']))
            $modx->error->addField('blockeduntil',$modx->lexicon('user_err_not_specified_blockeduntil'));
}

/* blocked after */
if (!empty($scriptProperties['blockedafter'])) {
    $scriptProperties['blockedafter'] = str_replace('-','/',$scriptProperties['blockedafter']);
    if (!$scriptProperties['blockedafter'] = strtotime($scriptProperties['blockedafter']))
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
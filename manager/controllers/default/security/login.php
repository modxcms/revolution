<?php
/**
 * Loads the login screen
 *
 * @package modx
 * @subpackage manager.security
 */
$modx->lexicon->load('login');
$modx->smarty->assign('_lang',$modx->lexicon->fetch());

if (isset($_REQUEST['modahsh'])) {
    $hash = $modx->sanitizeString($_REQUEST['modahsh']);
    $modx->smarty->assign('modahsh',$hash);
}
if (!empty($_SERVER['REQUEST_URI'])) {
    $chars = array("'",'"','(',')',';','>','<','!');
    $returnUrl = str_replace($chars,'',$_SERVER['REQUEST_URI']);
    $modx->smarty->assign('returnUrl',$returnUrl);
}

if (!empty($_POST)) {
    $san = array("'",'"','(',')',';','>','<','../');
    foreach ($_POST as $k => $v) {
        if (!in_array($k,array('returnUrl'))) {
            $_POST[$k] = str_replace($san,'',$v);
        } else {
            $chars = array("'",'"','(',')',';','>','<','!','../');
            $_POST[$k] = str_replace($chars,'',$v);
        }
    }
    $this->loadErrorHandler();
    $scriptProperties = $_REQUEST;

    /* handle login */
    if (!empty($_POST['login'])) {
        $validated = true;

        $user = $modx->getObject('modUser',array(
            'username' => $_POST['username'],
        ));
        /* first if there's an activation hash, process that */
        if ($user) {
            if (array_key_exists('modahsh', $_REQUEST) && !empty($_REQUEST['modahsh'])) {
                $activated = $user->activatePassword($_REQUEST['modahsh']);
                if ($activated === false) {
                    $modx->smarty->assign('error_message',$modx->lexicon('login_activation_key_err'));
                    $validated = false;
                }
            }
        }

        if ($validated) {
            $response = $modx->runProcessor('security/login',$_POST);
            if (($response instanceof modProcessorResponse) && !$response->isError()) {
                $url = !empty($_POST['returnUrl']) ? $_POST['returnUrl'] : $modx->getOption('manager_url',null,MODX_MANAGER_URL);
                $modx->sendRedirect(rtrim($url,'/'),'','','full');
            } else {
                $errors = $response->getAllErrors();
                $error_message = implode("\n",$errors);
                $modx->smarty->assign('error_message',$error_message);
            }
        }
    } else if (!empty($_POST['forgotlogin'])) {
        $c = $modx->newQuery('modUser');
        $c->select(array('modUser.*','Profile.email','Profile.fullname'));
        $c->innerJoin('modUserProfile','Profile');
        $c->where(array(
            'Profile.email' => $_POST['email'],
        ));
        $user = $modx->getObject('modUser',$c);
        if ($user) {
            $activationHash = md5(uniqid(md5($user->get('email') . '/' . $user->get('id')), true));

            $modx->getService('registry', 'registry.modRegistry');
            $modx->registry->getRegister('user', 'registry.modDbRegister');
            $modx->registry->user->connect();
            $modx->registry->user->subscribe('/pwd/reset/');
            $modx->registry->user->send('/pwd/reset/', array(md5($user->get('username')) => $activationHash), array('ttl' => 86400));

            $newPassword = $user->generatePassword();

            $user->set('cachepwd', $newPassword);
            $user->save();

            /* send activation email */
            $message = $modx->getOption('forgot_login_email');
            $placeholders = $user->toArray();
            $placeholders['url_scheme'] = $modx->getOption('url_scheme');
            $placeholders['http_host'] = $modx->getOption('http_host');
            $placeholders['manager_url'] = $modx->getOption('manager_url');
            $placeholders['hash'] = $activationHash;
            $placeholders['password'] = $newPassword;
            foreach ($placeholders as $k => $v) {
                $message = str_replace('[[+'.$k.']]',$v,$message);
            }

            $modx->getService('mail', 'mail.modPHPMailer');
            $modx->mail->set(modMail::MAIL_BODY, $message);
            $modx->mail->set(modMail::MAIL_FROM, $modx->getOption('emailsender'));
            $modx->mail->set(modMail::MAIL_FROM_NAME, $modx->getOption('site_name'));
            $modx->mail->set(modMail::MAIL_SENDER, $modx->getOption('emailsender'));
            $modx->mail->set(modMail::MAIL_SUBJECT, $modx->getOption('emailsubject'));
            $modx->mail->address('to', $user->get('email'),$user->get('fullname'));
            $modx->mail->address('reply-to', $modx->getOption('emailsender'));
            $modx->mail->setHTML(true);
            if (!$modx->mail->send()) {
                /* if for some reason error in email, tell user */
                $err = $modx->lexicon('error_sending_email_to').$user->get('email');
                $modx->log(modX::LOG_LEVEL_ERROR,$err);
                $modx->smarty->assign('error_message',$err);
            } else {
                $modx->smarty->assign('error_message',$modx->lexicon('login_password_reset_act_sent'));
            }
            $modx->mail->reset();
        } else {
            $modx->smarty->assign('error_message',$modx->lexicon('login_user_err_nf_email'));
        }
    }
    $modx->smarty->assign('_post',$_POST);
}

/* invoke OnManagerLoginFormPrerender event */
$eventInfo= $modx->invokeEvent('OnManagerLoginFormPrerender');
$eventInfo= is_array($eventInfo) ? implode("\n", $eventInfo) : (string) $eventInfo;
$modx->smarty->assign('onManagerLoginFormPrerender', $eventInfo);

if (isset($_REQUEST['installGoingOn'])) {
    $installGoingOn = $modx->sanitizeString($_REQUEST['installGoingOn']);
}
if (isset ($installGoingOn)) {
    switch ($installGoingOn) {
        case 1 : $modx->setPlaceholder('login_message',$modx->lexicon('login_cancelled_install_in_progress').$modx->lexicon('login_message')); break;
        case 2 : $modx->setPlaceholder('login_message',$modx->lexicon('login_cancelled_site_was_updated').$modx->lexicon('login_message')); break;
    }
}

/* invoke OnManagerLoginFormRender event */
$eventInfo= $modx->invokeEvent('OnManagerLoginFormRender');
$eventInfo= is_array($eventInfo) ? implode("\n", $eventInfo) : (string) $eventInfo;
$eventInfo= str_replace('\'','\\\'',$eventInfo);

$modx->smarty->assign('onManagerLoginFormRender', $eventInfo);
$modx->smarty->display('security/login.tpl');
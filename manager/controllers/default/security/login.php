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
    $modx->smarty->assign('modahsh',$_REQUEST['modahsh']);
}
if (!empty($_SERVER['REQUEST_URI'])) {
    $modx->smarty->assign('returnUrl',$_SERVER['REQUEST_URI']);
}

if (!empty($_POST)) {
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
            $cachepwd = $user->get('cachepwd');
            if (!empty($_REQUEST['modahsh']) && !empty($cachepwd)) {
                if ($_REQUEST['modahsh'] == md5($cachepwd)) {
                    /* correct hash and pwd specified, so reset actual pwd to new one */
                    $user->set('password',md5($cachepwd));
                    $user->set('cachepwd','');
                    $user->save();
                } else {
                    $modx->smarty->assign('error_message',$modx->lexicon('login_activation_key_err'));
                    $validated = false;
                }
            }
        } else {
            $modx->smarty->assign('error_message',$modx->lexicon('login_cannot_locate_account'));
            $validated = false;
        }

        if ($validated) {
            $processor = $modx->getOption('core_path').'model/modx/processors/security/login.php';
            $response = require_once $processor;

            if (!empty($response) && is_array($response)) {
                if (!empty($response['success']) && isset($response['object'])) {
                    $url = !empty($_POST['returnUrl']) ? $_POST['returnUrl'] : $modx->getOption('manager_url');
                    $modx->sendRedirect($url,'','','full');
                } else {
                    $error_message = '';
                    if (isset($response['errors']) && !empty($response['errors'])) {
                        foreach ($response['errors'] as $error) {
                            $error_message .= $error.'<br />';
                        }
                    } elseif (isset($response['message']) && !empty($response['message'])) {
                        $error_message = $response['message'];
                    } else {
                        $error_message = $modx->lexicon('login_err_unknown');
                    }
                    $modx->smarty->assign('error_message',$error_message);
                }
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

            $newPassword = $user->generatePassword();
            $newPasswordHash = md5($newPassword);

            $user->set('cachepwd',$newPassword);
            $user->save();

            /* send activation email */
            $message = $modx->getOption('forgot_login_email');
            $placeholders = $user->toArray();
            $placeholders['url_scheme'] = $modx->getOption('url_scheme');
            $placeholders['http_host'] = $modx->getOption('http_host');
            $placeholders['manager_url'] = $modx->getOption('manager_url');
            $placeholders['hash'] = $newPasswordHash;
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
    $installGoingOn = $_REQUEST['installGoingOn'];
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
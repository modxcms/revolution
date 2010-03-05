<?php
/**
 * Loads the login screen
 *
 * @package modx
 * @subpackage manager.security
 */
$modx->lexicon->load('login');
$modx->smarty->assign('_lang',$modx->lexicon->fetch());

if (!empty($_POST['login'])) {
    $this->loadErrorHandler();
    $scriptProperties = $_REQUEST;
    $processor = $modx->getOption('core_path').'model/modx/processors/security/login.php';
    $response = require_once $processor;

    if (!empty($response) && is_array($response)) {
        if (!empty($response['success']) && isset($response['object'])) {
            $url = $modx->getOption('manager_url');
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
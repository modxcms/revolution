<?php
/**
 * Loads the login screen
 *
 * @package modx
 * @subpackage manager.security
 */
$modx->lexicon->load('login');
$modx->smarty->assign('_lang',$modx->lexicon->fetch());

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
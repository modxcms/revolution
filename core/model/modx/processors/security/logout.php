<?php
/**
 * Properly log out the user, running any events and flushing the session.
 *
 * @package modx
 * @subpackage processors.security
 */
if (!isset($modx->lexicon) || !is_object($modx->lexicon)) {
    $modx->getService('lexicon','modLexicon');
}
$modx->lexicon->load('login');

$loginContext= isset ($_REQUEST['login_context']) ? $_REQUEST['login_context'] : $modx->context->get('key');

if (!$modx->user->isAuthenticated($loginContext)) return $modx->error->failure($modx->lexicon('not_logged_in'));

if ($loginContext == 'mgr') {
    /* invoke OnBeforeManagerLogout event */
    $modx->invokeEvent('OnBeforeManagerLogout',array(
        'userid' => $modx->user->get('id'),
        'username' => $modx->user->get('username'),
    ));
} else {
    $modx->invokeEvent('OnBeforeWebLogout',array(
        'userid' => $modx->user->get('id'),
        'username' => $modx->user->get('username'),
    ));
}

$modx->user->removeSessionContext($loginContext);

if ($loginContext == 'mgr') {
    /* invoke OnManagerLogout event */
    $modx->invokeEvent('OnManagerLogout',array(
        'userid' => $modx->user->get('id'),
        'username' => $modx->user->get('username'),
    ));
} else {
    $modx->invokeEvent('OnWebLogout',array(
        'userid' => $modx->user->get('id'),
        'username' => $modx->user->get('username'),
    ));
}

return $modx->error->success();
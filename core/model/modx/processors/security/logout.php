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

$loginContext= isset ($scriptProperties['login_context']) ? $scriptProperties['login_context'] : $modx->context->get('key');
$addContexts= isset ($scriptProperties['add_contexts']) && !empty($scriptProperties['add_contexts']) ? explode(',', $scriptProperties['add_contexts']) : array();

if ($modx->user->isAuthenticated($loginContext)) {
    if ($loginContext == 'mgr') {
        /* invoke OnBeforeManagerLogout event */
        $modx->invokeEvent('OnBeforeManagerLogout',array(
            'userid' => $modx->user->get('id'),
            'username' => $modx->user->get('username'),
            'user' => &$modx->user,
            'loginContext' => &$loginContext,
            'addContexts' => &$addContexts
        ));
    } else {
        $modx->invokeEvent('OnBeforeWebLogout',array(
            'userid' => $modx->user->get('id'),
            'username' => $modx->user->get('username'),
            'user' => &$modx->user,
            'loginContext' => &$loginContext,
            'addContexts' => &$addContexts
        ));
    }

    $modx->user->removeSessionContext($loginContext);
    if (!empty($addContexts)) {
        foreach ($addContexts as $addCtx) {
            $modx->user->removeSessionContext($addCtx);
        }
    }
    
    if ($loginContext == 'mgr') {
        /* invoke OnManagerLogout event */
        $modx->invokeEvent('OnManagerLogout',array(
            'userid' => $modx->user->get('id'),
            'username' => $modx->user->get('username'),
            'user' => &$modx->user,
            'loginContext' => &$loginContext,
            'addContexts' => &$addContexts
        ));
    } else {
        $modx->invokeEvent('OnWebLogout',array(
            'userid' => $modx->user->get('id'),
            'username' => $modx->user->get('username'),
            'user' => &$modx->user,
            'loginContext' => &$loginContext,
            'addContexts' => &$addContexts
        ));
    }
} else {
    return $modx->error->failure($modx->lexicon('not_logged_in'));
}
return $modx->error->success();
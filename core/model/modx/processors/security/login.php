<?php
/**
 * Properly log in the user and set up the session.
 *
 * @package modx
 * @subpackage processors.security
 */
if (!isset($modx->lexicon) || !is_object($modx->lexicon)) {
    $modx->getService('lexicon','modLexicon');
}
$modx->lexicon->load('login');

$username = $scriptProperties['username'];
$givenPassword = $scriptProperties['password'];

$rememberme= isset ($scriptProperties['rememberme']) ? ($scriptProperties['rememberme'] == 'on' || $scriptProperties['rememberme'] == true) : false;
$lifetime= (integer) $modx->getOption('lifetime', $scriptProperties, $modx->getOption('session_cookie_lifetime', null, 0));
$loginContext= isset ($scriptProperties['login_context']) ? $scriptProperties['login_context'] : $modx->context->get('key');

$onBeforeLoginParams = array(
    'username' => $username,
    'password' => $givenPassword,
    'attributes' => array(
        'rememberme' => & $rememberme,
        'lifetime' => & $lifetime,
        'loginContext' => & $loginContext
    )
);

$rt = false;  /* $rt will be an array if the event fires */
if ($loginContext == 'mgr') {
    $rt = $modx->invokeEvent("OnBeforeManagerLogin", $onBeforeLoginParams);
} else {
    $rt = $modx->invokeEvent("OnBeforeWebLogin", $onBeforeLoginParams);
}
/* If the event fired, loop through the event array and fail if there's an error message  */
if (is_array($rt)) {
    foreach ($rt as $key => $value) {   /* php4 compatible */
        if ($value !== true) {
            return $modx->error->failure($value);
        }
    }
    unset($key,$value);
}

$user= $modx->getObjectGraph('modUser', '{"Profile":{},"UserSettings":{}}', array ('modUser.username' => $username));

if (!$user) {
    $ru = $modx->invokeEvent("OnUserNotFound", array(
        'user' => &$user,
        'username' => $username,
        'password' => $givenPassword,
        'attributes' => array(
            'rememberme' => $rememberme,
            'lifetime' => $lifetime,
            'loginContext' => $loginContext,
        )
    ));
    if (!empty($ru)) {
        foreach ($ru as $obj) {
            if (is_object($obj) && $obj instanceof modUser) {
                $user = $obj;
                break;
            }
        }
    }
    if (!is_object($user) || !($user instanceof modUser)) {
        return $modx->error->failure($modx->lexicon('login_cannot_locate_account'));
    }
}

if (!$user->get('active')) {
    return $modx->error->failure($modx->lexicon('login_user_inactive'));
}

$up= & $user->Profile;
$us= & $user->UserSettings;
foreach ($us as $settingPK => $setting) {
    $sname= $setting->get('key');
    $$sname= $setting->get('value');
}
if ($up->get('failed_logins') >= $modx->getOption('failed_login_attempts') && $up->get('blockeduntil') > time()) {
    return $modx->error->failure($modx->lexicon('login_blocked_too_many_attempts'));
}
if ($up->get('failedlogincount') >= $modx->getOption('failed_login_attempts') && $up->get('blockeduntil') < time()) {
    $up->set('failedlogincount', 0);
    $up->set('blockeduntil', time() - 1);
    $up->save();
}
if ($up->get('blocked')) {
    return $modx->error->failure($modx->lexicon('login_blocked_admin'));
}
if ($up->get('blockeduntil') > time()) {
    return $modx->error->failure($modx->lexicon('login_blocked_error'));
}
if ($up->get('blockedafter') > 0 && $up->get('blockedafter') < time()) {
    return $modx->error->failure($modx->lexicon('login_blocked_error'));
}
if (isset ($allowed_ip) && $allowed_ip) {
    if (($hostname = gethostbyaddr($_SERVER['REMOTE_ADDR'])) && ($hostname != $_SERVER['REMOTE_ADDR'])) {
        if (gethostbyname($hostname) != $_SERVER['REMOTE_ADDR']) {
            return $modx->error->failure($modx->lexicon('login_hostname_error'));
        }
    }
    if (!in_array($_SERVER['REMOTE_ADDR'], explode(',', str_replace(' ', '', $allowed_ip)))) {
        return $modx->error->failure($modx->lexicon('login_blocked_ip'));
    }
}
if (isset ($allowed_days) && $allowed_days) {
    $date = getdate();
    $day = $date['wday'] + 1;
    if (strpos($allowed_days, "{$day}") === false) {
        return $modx->error->failure($modx->lexicon('login_blocked_time'));
    }
}

$loginAttributes = array(
    "user"       => & $user,
    "password"   => $givenPassword,
    "rememberme" => $rememberme,
    "lifetime" => $lifetime
);
if ($loginContext == 'mgr') {
    $rt = $modx->invokeEvent("OnManagerAuthentication", $loginAttributes);
} else {
    $rt = $modx->invokeEvent("OnWebAuthentication", $loginAttributes);
}
/* check if plugin authenticated the user */
if (!$rt || (is_array($rt) && !in_array(true, $rt))) {
    /* check user password - local authentication */
    if($user->get('password') != md5($givenPassword)) {
        return $modx->error->failure($modx->lexicon('login_username_password_incorrect'));
    }
}

$user->addSessionContext($loginContext);

if ($rememberme) {
    $_SESSION['modx.' . $loginContext . '.session.cookie.lifetime']= $lifetime;
} else {
    $_SESSION['modx.' . $loginContext . '.session.cookie.lifetime']= 0;
}

$postLoginAttributes = array(
    'user' => $user,
    'attributes' => array(
        'rememberme' => $rememberme,
        'lifetime' => $lifetime,
        'loginContext' => $loginContext
    )
);
if ($loginContext == 'mgr') {
    $rt = $modx->invokeEvent("OnManagerLogin", $postLoginAttributes);
} else {
    $modx->invokeEvent("OnWebLogin", $postLoginAttributes);
}
$returnUrl = isset($scriptProperties['returnUrl']) ? $scriptProperties['returnUrl'] : '';
$response = array('url' => $returnUrl);
switch ($loginContext) {
    case 'mgr':
        $manager_login_startup_url = $modx->getOption('manager_url', null, $returnUrl);
        if (!empty($manager_login_startup)) {
            $manager_login_startup= intval($manager_login_startup);
            if ($manager_login_startup) $manager_login_startup_url .= '?id=' . $manager_login_startup;
        }
        $response= array('url' => $manager_login_startup_url);
        break;
    case 'web':
    default:
        $login_startup_url = $returnUrl;
        if (!empty($login_startup)) {
            $login_startup = intval($login_startup);
            if ($login_startup) $login_startup_url = $modx->makeUrl($login_startup, $loginContext, '', 'full');
        }
        $response= array('url' => $login_startup_url);
}

return $modx->error->success('', $response);
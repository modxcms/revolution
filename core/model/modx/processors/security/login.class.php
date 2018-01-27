<?php
/**
 * Properly log in the user and set up the session.
 *
 * @package modx
 * @subpackage processors.security
 */

class modSecurityLoginProcessor extends modProcessor {

    /** @var  modUser */
    public $user;
    public $username;
    public $givenPassword;
    public $rememberme;
    public $lifetime;
    public $loginContext;
    public $addContexts;
    public $isMgr;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $this->username = $this->getProperty('username');
        $this->givenPassword = $this->getProperty('password');
        if (!$this->username || !$this->givenPassword) {
            return $this->modx->lexicon('login_cannot_locate_account');
        }

        $this->rememberme = ($this->getProperty('rememberme', false) == true);
        $this->lifetime = (int)$this->getProperty('lifetime', $this->modx->getOption('session_cookie_lifetime', null,0));
        $this->loginContext = $this->getProperty('login_context', $this->modx->context->get('key'));
        $this->addContexts = $this->getProperty('add_contexts', array());
        $this->addContexts = empty($this->addContexts) ? array() : explode(',', $this->addContexts);
        /* Events are fired based on the primary loginContext */
        $this->isMgr = ($this->loginContext == 'mgr');

        return true;
    }

    public function getLanguageTopics() {
        return array('login');
    }

    /**
     * Fire event at the start of login process
     * @return string
     */
    public function fireOnBeforeLoginEvent() {
        $onBeforeLoginParams = array(
            'username' => $this->username,
            'password' => $this->givenPassword,
            'attributes' => array(
                'rememberme' => & $this->rememberme,
                'lifetime' => & $this->lifetime,
                'loginContext' => & $this->loginContext,
                'addContexts' => & $this->addContexts,
            )
        );

        $response = $this->modx->invokeEvent($this->isMgr ? "OnBeforeManagerLogin" : "OnBeforeWebLogin", $onBeforeLoginParams);

        if (is_array($response)) {
            foreach ($response as $key => $value) {
                if ($value !== true) {
                    return $value;
                }
            }
        }

        return '';
    }

    /**
     * Load user with profile and user settings
     * @return bool|null|string
     */
    public function getUser() {
        /** @var $user modUser */
        $this->user = $this->modx->getObjectGraph('modUser', '{"Profile":{},"UserSettings":{}}',
            array ('modUser.username' => $this->username));

        return $this->fireOnUserNotFoundEvent();
    }

    /**
     * Fire event when user with this username is not found
     * @return bool|null|string
     */
    public function fireOnUserNotFoundEvent() {
        if (!$this->user) {
            $OnUserNotFoundParams = array(
                'user' => &$this->user,
                'username' => $this->username,
                'password' => $this->givenPassword,
                'attributes' => array(
                    'rememberme' => $this->rememberme,
                    'lifetime' => $this->lifetime,
                    'loginContext' => $this->loginContext,
                    'addContexts' => $this->addContexts,
                )
            );
            $ru = $this->modx->invokeEvent("OnUserNotFound", $OnUserNotFoundParams);
            if (!empty($ru)) {
                foreach ($ru as $obj) {
                    if (is_object($obj) && $obj instanceof modUser) {
                        $this->user = $obj;
                        break;
                    }
                }
            }
            if (!is_object($this->user) || !($this->user instanceof modUser)) {
                return $this->modx->lexicon('login_cannot_locate_account');
            }
        }

        return false;
    }

    /**
     * Check if user is not active or blocked
     * @return bool|null|string
     */
    public function checkIsBlocked() {
        if (!$this->user->get('active')) {
            return $this->modx->lexicon('login_user_inactive');
        }

        /** @var modUserProfile $profile */
        $profile = $this->user->Profile;

        if ($profile->get('failed_logins') >= $this->modx->getOption('failed_login_attempts') &&
            $profile->get('blockeduntil') > time()) {
            return $this->modx->lexicon('login_blocked_too_many_attempts');
        }

        if ($profile->get('failedlogincount') >= $this->modx->getOption('failed_login_attempts')) {
            $profile->set('failedlogincount', 0);
            $profile->set('blocked', 1);
            $profile->set('blockeduntil', time() + (60 * $this->modx->getOption('blocked_minutes')));
            $profile->save();
        }
        if ($profile->get('blockeduntil') != 0 && $profile->get('blockeduntil') < time()) {
            $profile->set('failedlogincount', 0);
            $profile->set('blocked', 0);
            $profile->set('blockeduntil', 0);
            $profile->save();
        }
        if ($profile->get('blocked')) {
            return $this->modx->lexicon('login_blocked_admin');
        }
        if ($profile->get('blockeduntil') > time()) {
            return $this->modx->lexicon('login_blocked_error');
        }
        if ($profile->get('blockedafter') > 0 && $profile->get('blockedafter') < time()) {
            return $this->modx->lexicon('login_blocked_error');
        }

        return false;
    }

    /**
     * Check user settings related to authentication
     * @return bool|null|string
     */
    public function checkUserSettings() {
        /**
         * @var string $settingPK
         * @var modUserSetting $setting
         */
        foreach ($this->user->UserSettings as $settingPK => $setting) {
            if ($setting->get('key') == 'allowed_ip') {
                $ip = $this->modx->request->getClientIp();
                $ip = $ip['ip'];
                if (!in_array($ip, explode(',', str_replace(' ', '', $setting->get('value'))))) {
                    return $this->modx->lexicon('login_blocked_ip');
                }
            }

            if ($setting->get('key') == 'allowed_days') {
                $date = getdate();
                $day = $date['wday'] + 1;
                if (strpos($setting->get('value'), "{$day}") === false) {
                    return $this->modx->lexicon('login_blocked_time');
                }
            }
        }

        return false;
    }

    /**
     * Actions before user is logged in
     * @return bool|null|string
     */
    public function beforeLogin() {
        $preventLogin = $this->fireOnBeforeLoginEvent();
        if (!empty($preventLogin)) {
            return $preventLogin;
        }

        $preventLogin = $this->getUser();
        if (!empty($preventLogin)) {
            return $preventLogin;
        }

        $preventLogin = $this->checkIsBlocked();
        if (!empty($preventLogin)) {
            return $preventLogin;
        }

        $preventLogin = $this->checkUserSettings();
        if (!empty($preventLogin)) {
            return $preventLogin;
        }

        return false;
    }

    /**
     * Fire event just before password check
     * @return array|bool
     */
    public function fireOnAuthenticationEvent() {
        $loginParams = array(
            "user"       => & $this->user,
            "password"   => $this->givenPassword,
            "rememberme" => $this->rememberme,
            "lifetime" => $this->lifetime,
            "loginContext" => & $this->loginContext,
            "addContexts" => & $this->addContexts,
        );

        return $this->modx->invokeEvent($this->isMgr ? "OnManagerAuthentication" : "OnWebAuthentication", $loginParams);
    }

    /**
     * Update failed login count
     */
    public function failedLogin() {
        if (!isset($_SESSION['login_failed'])) {
            $_SESSION['login_failed'] = 0;
        }
        $flc = ((integer) $this->user->Profile->get('failedlogincount')) + 1;
        $this->user->Profile->set('failedlogincount', $flc);
        $this->user->Profile->save();
        $_SESSION['login_failed']++;
    }

    /** Check user password
     *
     * @param $rt
     * @return bool|null|string
     */
    public function checkPassword($rt) {
        /* check if plugin authenticated the user */
        if (!$rt || (is_array($rt) && !in_array(true, $rt))) {
            /* check user password - local authentication */
            if (!$this->user->passwordMatches($this->givenPassword)) {
                $this->failedLogin();
                return $this->modx->lexicon('login_username_password_incorrect');
            }
        }
        else if ($rt && (is_array($rt) && !in_array(true, $rt, true))) {
            $error = "";
            foreach ($rt as $msg) {
                if (!empty($msg)) {
                    $error .= $msg."\n";
                }
            }
            return $error;
        }

        return false;
    }

    /**
     * Remember user in session by login contexts
     */
    public function addSessionContexts() {
        $contexts = array_merge(array($this->loginContext), $this->addContexts);
        foreach ($contexts as $loginCtx) {
            $this->user->addSessionContext($loginCtx);
            $_SESSION['modx.' . $loginCtx . '.session.cookie.lifetime'] = $this->rememberme ? $this->lifetime : 0;
        }
    }

    /**
     * Fire after login event
     */
    public function fireAfterLoginEvent() {
        $postLoginParams = array(
            'user' => $this->user,
            'attributes' => array(
                'rememberme' => $this->rememberme,
                'lifetime' => $this->lifetime,
                'loginContext' => $this->loginContext,
                'addContexts' => $this->addContexts,
            )
        );

        $this->modx->invokeEvent($this->isMgr ? "OnManagerLogin" : "OnWebLogin", $postLoginParams);
    }

    /** Prepare response for mgr context
     *
     * @param $userToken
     * @param $returnUrl
     * @return array
     */
    public function prepareMgrResponse($userToken, $returnUrl) {
        $managerUrl = $this->modx->getOption('url_scheme') . $this->modx->getOption('http_host') . $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
        $manager_login_startup_url = !empty($returnUrl) && (strpos($returnUrl, '://') === false || strpos($returnUrl, $managerUrl) === 0)
            ? $returnUrl
            : $managerUrl;
        if (!empty($manager_login_startup)) {
            $manager_login_startup= intval($manager_login_startup);
            if ($manager_login_startup) $manager_login_startup_url .= '?id=' . $manager_login_startup;
        }
        return array(
            'url' => $manager_login_startup_url,
            'token' => $userToken,
        );
    }

    /** Prepare response for non-mgr contexts
     *
     * @param $userToken
     * @param $returnUrl
     * @return array
     */
    public function prepareWebResponse($userToken, $returnUrl) {
        $siteUrl = $this->modx->getOption('site_url', null, MODX_SITE_URL);
        $login_startup_url = !empty($returnUrl) && (strpos($returnUrl, '://') === false || strpos($returnUrl, $siteUrl) === 0)
            ? $returnUrl
            : '';
        if (!empty($login_startup)) {
            $login_startup = intval($login_startup);
            if ($login_startup) {
                $login_startup_url = $this->modx->makeUrl($login_startup, $this->loginContext, '', 'full');
            }
        }
        return array(
            'url' => $login_startup_url,
            'token' => $userToken,
        );
    }

    /** Prepare response depending on the login context
     *
     * @return array
     */
    public function prepareResponse() {
        $userToken = $this->user->getUserToken($this->modx->context->get('key'));
        $returnUrl = $this->getProperty('returnUrl', '');

        switch ($this->loginContext) {
            case 'mgr':
                $response = $this->prepareMgrResponse($userToken, $returnUrl);
                break;
            case 'web':
            default:
                $response = $this->prepareWebResponse($userToken, $returnUrl);
        }

        return $response;
    }

    /** Actions after user is logged in
     *
     * @return array
     */
    public function afterLogin() {
        $this->addSessionContexts();
        $this->fireAfterLoginEvent();

        $this->modx->logManagerAction('login','modContext',$this->loginContext, $this->user->get('id'));

        return $this->prepareResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function process() {
        $preventLogin = $this->beforeLogin();
        if (!empty($preventLogin)) {
            return $this->failure($preventLogin);
        }

        $canLogin = $this->fireOnAuthenticationEvent();
        $preventLogin = $this->checkPassword($canLogin);
        if (!empty($preventLogin)) {
            return $this->failure($preventLogin);
        }

        $response = $this->afterLogin();
        return $this->cleanup($response);
    }

    /** Return the response
     *
     * @param $response array
     * @return array
     */
    public function cleanup($response) {
        return $this->success('', $response);
    }
}

return 'modSecurityLoginProcessor';

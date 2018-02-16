<?php
/**
 * Loads the login screen
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityLoginManagerController extends modManagerController {
    public $loadHeader = false;
    public $loadFooter = false;

    public function initialize() {
        $this->handleLanguageChange();
        return true;
    }
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return true;
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {}

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $this->handleForgotLoginHash();
        $this->preserveReturnUrl();

        if (!empty($this->scriptProperties)) {
            $this->handlePost();
        }

        /* invoke OnManagerLoginFormPrerender event */
        $eventInfo= $this->modx->invokeEvent('OnManagerLoginFormPrerender');
        $eventInfo= is_array($eventInfo) ? implode("\n", $eventInfo) : (string) $eventInfo;
        $this->setPlaceholder('onManagerLoginFormPrerender', $eventInfo);

        $hour = date('H') + (int)$this->modx->getOption('server_offset');
        if ($hour > 18) {
            $greeting = $this->modx->lexicon('login_greeting_evening');
        }
        elseif ($hour > 12) {
            $greeting = $this->modx->lexicon('login_greeting_afternoon');
        }
        elseif ($hour > 6) {
            $greeting = $this->modx->lexicon('login_greeting_morning');
        }
        else {
            $greeting = $this->modx->lexicon('login_greeting_night');
        }
        $this->setPlaceholder('greeting', $greeting);

        $managerUrl = $this->modx->getOption('manager_url');
        $background = $this->modx->getOption('login_background_image', null, $managerUrl . 'templates/default/images/login/default-background.jpg', true);
        $this->setPlaceholder('background', $background);

        $background = $this->modx->getOption('login_logo', null, $managerUrl . 'templates/default/images/modx-logo-color.svg', true);
        $this->setPlaceholder('logo', $background);

        $this->setPlaceholder('siteUrl', $this->modx->getOption('site_url'));

        $this->setPlaceholder('show_help', (int)$this->modx->getOption('login_help_button'));
        $lifetime = $this->modx->getOption('session_cookie_lifetime', null, 0);
        $this->setPlaceholder('rememberme', $output = $this->modx->lexicon('login_remember', array('lifetime' => $this->getLifetimeString($lifetime))));
        

        $this->checkForActiveInstallation();
        $this->checkForAllowManagerForgotPassword();

        /* invoke OnManagerLoginFormRender event */
        $eventInfo= $this->modx->invokeEvent('OnManagerLoginFormRender');
        $eventInfo= is_array($eventInfo) ? implode("\n", $eventInfo) : (string) $eventInfo;
        $eventInfo= str_replace('\'','\\\'',$eventInfo);
        $this->setPlaceholder('onManagerLoginFormRender', $eventInfo);
    }

    public function getLifetimeString($diff) {
        $this->modx->lexicon->load('filters');
        $agoTS = array();

        $years = intval((floor($diff/31536000)));
        if ($years) $diff = $diff % 31536000;

        $months = intval((floor($diff/2628000)));
        if ($months) $diff = $diff % 2628000;

        $weeks = intval((floor($diff/604800)));
        if ($weeks) $diff = $diff % 604800;

        $days = intval((floor($diff/86400)));
        if ($days) $diff = $diff % 86400;

        $hours = intval((floor($diff/3600)));
        if ($hours) $diff = $diff % 3600;

        $minutes = intval((floor($diff/60)));
        if ($minutes) $diff = $diff % 60;

        $diff = intval($diff);
        $agoTS = array(
            'years' => $years,
            'months' => $months,
            'weeks' => $weeks,
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $diff,
        );

        $ago = array();
        if (!empty($agoTS['years'])) {
            $ago[] = $this->modx->lexicon(($agoTS['years'] > 1 ? 'ago_years' : 'ago_year'),array('time' => $agoTS['years']));
        }
        if (!empty($agoTS['months'])) {
            $ago[] = $this->modx->lexicon(($agoTS['months'] > 1 ? 'ago_months' : 'ago_month'),array('time' => $agoTS['months']));
        }
        if (!empty($agoTS['weeks']) && empty($agoTS['years'])) {
            $ago[] = $this->modx->lexicon(($agoTS['weeks'] > 1 ? 'ago_weeks' : 'ago_week'),array('time' => $agoTS['weeks']));
        }
        if (!empty($agoTS['days']) && empty($agoTS['months']) && empty($agoTS['years'])) {
            $ago[] = $this->modx->lexicon(($agoTS['days'] > 1 ? 'ago_days' : 'ago_day'),array('time' => $agoTS['days']));
        }
        if (!empty($agoTS['hours']) && empty($agoTS['weeks']) && empty($agoTS['months']) && empty($agoTS['years'])) {
            $ago[] = $this->modx->lexicon(($agoTS['hours'] > 1 ? 'ago_hours' : 'ago_hour'),array('time' => $agoTS['hours']));
        }
        if (!empty($agoTS['minutes']) && empty($agoTS['days']) && empty($agoTS['weeks']) && empty($agoTS['months']) && empty($agoTS['years'])) {
            $ago[] = $this->modx->lexicon($agoTS['minutes'] == 1 ? 'ago_minute' : 'ago_minutes' ,array('time' => $agoTS['minutes']));
        }
        if (empty($ago)) { /* handle <1 min */
            $ago[] = $this->modx->lexicon('ago_seconds',array('time' => !empty($agoTS['seconds']) ? $agoTS['seconds'] : 0));
        }
        $output = implode(', ',$ago);
        return $output;
    }

    /**
     * Set the cultureKey for the login page and get the list of languages
     * @return string The loaded cultureKey
     */
    public function handleLanguageChange() {
        $cultureKey = $this->modx->getOption('cultureKey',$_REQUEST,'en');
        if ($cultureKey) {
            $cultureKey = $this->modx->sanitizeString($cultureKey);
            $this->modx->setOption('cultureKey',$cultureKey);
            $this->modx->setOption('manager_language',$cultureKey);
        }
        $this->setPlaceholder('cultureKey',$cultureKey);


        $languages = $this->modx->lexicon->getLanguageList('core');
        $this->modx->lexicon->load('core:languages_native');

        $list = array();
        foreach ($languages as $language) {
            $native = $this->modx->lexicon('language_native_'.$language, array());
            $selected = $language == $cultureKey ? ' selected="selected"' : '';
            $list[] = '<option lang="'.$language.'" value="'.$language.'"'.$selected.'>'.$native.'</option>';
        }
        $this->setPlaceholder('languages',implode("\n",$list));

        $this->modx->lexicon->load('login');
        $languageString = $this->modx->lexicon('login_language');
        if (empty($languageString) || strcmp($languageString,'login_language') == 0) {
            $this->modx->lexicon->load('en:core:login');
            $languageString = $this->modx->lexicon('login_language',array(),'en');
        }
        $this->setPlaceholder('language_str',$languageString);

        return $cultureKey;
    }

    public function checkForAllowManagerForgotPassword() {
        $allow = $this->modx->getOption('allow_manager_login_forgot_password',null,true);
        if ($allow) {
            $this->setPlaceholder('allow_forgot_password',true);
        }
    }

    /**
     * Handle and sanitize the forgot login hash, if existent
     *
     * @return void
     */
    public function handleForgotLoginHash() {
        if (isset($_GET['modahsh'])) {
            $this->scriptProperties['modahsh'] = $this->modx->sanitizeString($_GET['modahsh']);
            $this->setPlaceholder('modahsh',$this->scriptProperties['modahsh']);
        }
    }

    /**
     * If the user is coming from a specific mgr action, preserve the return URL and redirect post-login
     * @return void
     */
    public function preserveReturnUrl() {
        if (!empty($_SERVER['REQUEST_URI'])) {
            $chars = array("'",'"','(',')',';','>','<','!');
            $returnUrl = str_replace($chars,'',$_SERVER['REQUEST_URI']);
            $this->setPlaceholder('returnUrl',$returnUrl);
        }
    }

    /**
     * Check to see if there's an active installation in process; if so, notify the user.
     * @return void
     */
    public function checkForActiveInstallation() {
        if (isset($this->scriptProperties['installGoingOn'])) {
            $installGoingOn = $this->modx->sanitizeString($this->scriptProperties['installGoingOn']);
        }
        if (isset ($installGoingOn)) {
            switch ($installGoingOn) {
                case 1 : $this->setPlaceholder('login_message',$this->modx->lexicon('login_cancelled_install_in_progress').$this->modx->lexicon('login_message')); break;
                case 2 : $this->setPlaceholder('login_message',$this->modx->lexicon('login_cancelled_site_was_updated').$this->modx->lexicon('login_message')); break;
            }
        }
    }

    /**
     * Handle and sanitize any POST actions that come through
     *
     * @return void
     */
    public function handlePost() {
        $san = array("'",'"','(',')',';','>','<','../');
        foreach ($this->scriptProperties as $k => $v) {
            if (!in_array($k,array('returnUrl'))) {
                $this->scriptProperties[$k] = str_replace($san,'',$v);
            } else {
                $chars = array("'",'"','(',')',';','>','<','!','../');
                $this->scriptProperties[$k] = str_replace($chars,'',$v);
            }
        }

        /* handle login */
        if (!empty($this->scriptProperties['login'])) {
            $this->handleLogin();
        } else if (!empty($this->scriptProperties['forgotlogin']) && $this->modx->getOption('allow_manager_login_forgot_password',null,true)) {
            $this->handleForgotLogin();
        }
        $this->setPlaceholder('_post',$this->scriptProperties);
    }

    /**
     * Handle when a user attempts to log in
     * @return void
     */
    public function handleLogin() {
        $validated = true;

        /** @var modUser $user */
        $user = $this->modx->getObject('modUser',array(
            'username' => $this->scriptProperties['username'],
        ));

        /* first if there's an activation hash, process that */
        if ($user) {
            if (array_key_exists('modahsh', $this->scriptProperties) && !empty($this->scriptProperties['modahsh'])) {
                $activated = $user->activatePassword($this->scriptProperties['modahsh']);
                if ($activated === false) {
                    $this->modx->smarty->assign('error_message',$this->modx->lexicon('login_activation_key_err'));
                    $validated = false;
                }
            }
        }

        if ($validated) {
            /** @var modProcessorResponse $response */
            $response = $this->modx->runProcessor('security/login',$this->scriptProperties);
            if (($response instanceof modProcessorResponse) && !$response->isError()) {
                $url = !empty($this->scriptProperties['returnUrl']) ? $this->scriptProperties['returnUrl'] : $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
                $url = $this->modx->getOption('url_scheme', null, MODX_URL_SCHEME).$this->modx->getOption('http_host', null, MODX_HTTP_HOST).rtrim($url,'/');
                $this->modx->sendRedirect($url);
            } else {
                $errors = $response->getAllErrors();
                $error_message = implode("\n",$errors);
                $this->setPlaceholder('error_message',$error_message);
            }
        }
    }

    /**
     * Handles the action when a user forgets their login
     *
     * @return void
     */
    public function handleForgotLogin() {
        $c = $this->modx->newQuery('modUser');
        $c->select(array('modUser.*','Profile.email','Profile.fullname'));
        $c->innerJoin('modUserProfile','Profile');
        $c->where(array(
            'modUser.username' => $this->scriptProperties['username_reset'],
            'OR:Profile.email:=' => $this->scriptProperties['username_reset'],
        ));
        /** @var modUser $user */
        $user = $this->modx->getObject('modUser',$c);
        if ($user) {
            $activationHash = md5(uniqid(md5($user->get('email') . '/' . $user->get('id')), true));

            $this->modx->getService('registry', 'registry.modRegistry');
            $this->modx->registry->getRegister('user', 'registry.modDbRegister');
            $this->modx->registry->user->connect();
            $this->modx->registry->user->subscribe('/pwd/reset/');
            $this->modx->registry->user->send('/pwd/reset/', array(md5($user->get('username')) => $activationHash), array('ttl' => 86400));

            $newPassword = $user->generatePassword();

            $user->set('cachepwd', $newPassword);
            $user->save();

            /* send activation email */
            $message = $this->modx->getOption('forgot_login_email');
            $placeholders = $user->toArray();
            $placeholders['url_scheme'] = $this->modx->getOption('url_scheme');
            $placeholders['http_host'] = $this->modx->getOption('http_host');
            $placeholders['manager_url'] = $this->modx->getOption('manager_url');
            $placeholders['hash'] = $activationHash;
            $placeholders['password'] = $newPassword;
            // Store previous placeholders
            $ph = $this->modx->placeholders;
            // now set those useful for modParser
            $this->modx->setPlaceholders($placeholders);
            $this->modx->getParser()->processElementTags('', $message, false, false);
            // Then restore previous placeholders to prevent any breakage
            $this->modx->placeholders = $ph;

            $this->modx->getService('mail', 'mail.modPHPMailer');
            $this->modx->mail->set(modMail::MAIL_BODY, $message);
            $this->modx->mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
            $this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
            $this->modx->mail->set(modMail::MAIL_SENDER, $this->modx->getOption('emailsender'));
            $this->modx->mail->set(modMail::MAIL_SUBJECT, $this->modx->getOption('emailsubject'));
            $this->modx->mail->address('to', $user->get('email'),$user->get('fullname'));
            $this->modx->mail->address('reply-to', $this->modx->getOption('emailsender'));
            $this->modx->mail->setHTML(true);
            if (!$this->modx->mail->send()) {
                /* if for some reason error in email, tell user */
                $err = $this->modx->lexicon('error_sending_email_to').$user->get('email');
                $this->modx->log(modX::LOG_LEVEL_ERROR,$err);
                $this->setPlaceholder('error_message',$err);
            } else {
                $this->setPlaceholder('error_message',$this->modx->lexicon('login_password_reset_act_sent'));
            }
            $this->modx->mail->reset();
        } else {
            $this->setPlaceholder('error_message',$this->modx->lexicon('login_user_err_nf_email'));
        }
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('login');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'security/login.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('login');
    }
}

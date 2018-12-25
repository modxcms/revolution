<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Loads the login screen
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityLoginManagerController extends modManagerController
{
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
     * @return void
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

        // Define background
        $managerTheme = $this->modx->getOption('manager_theme', null, 'default');
        $file_path = MODX_MANAGER_PATH . 'templates/' . $managerTheme . '/images/login/';
        $file_url = MODX_MANAGER_URL . 'templates/' . $managerTheme . '/images/login/';
        $this->modx->getVersionData();
        $version = preg_replace('#-.*$#', '', $this->modx->version['full_version']);
        $background = $this->modx->getOption('login_background_image');
        $season = '';
        if (!$background || $background == 'auto') {
            $month = date('m');
            if ($month >= 3 && $month <= 5) {
                $season = 'spring';
            } elseif ($month >= 6 && $month <= 8) {
                $season = 'summer';
            } elseif ($month >= 9 && $month <= 11) {
                $season = 'autumn';
            } else {
                $season = 'winter';
            }
            $background = '';
        } elseif (in_array($background, ['spring', 'summer', 'autumn', 'winter'])) {
            $season = $background;
            $background = '';
        }
        if ($season) {
            $file_name = $season . '-' . $version . '.jpg';
            if (file_exists($file_path . $file_name)) {
                $background = $file_url . $file_name;
            }
        }
        if (!$background) {
            $background = $managerUrl . 'templates/' . $managerTheme . '/images/login/default-background.jpg';
        }
        $this->setPlaceholder('background', $background);
        // --

        $logo = $this->modx->getOption('login_logo', null, $managerUrl . 'templates/' . $managerTheme . '/images/modx-logo-color.svg', true);
        $this->setPlaceholder('logo', $logo);

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
     * Determine and save the user cultureKey so it could be used across the whole manager
     *
     * @return string The loaded cultureKey
     */
    public function handleLanguageChange()
    {
        $languages = $this->modx->lexicon->getLanguageList('core');

        $showing = array_flip($languages);
        array_walk($showing, function (&$language, $key) {
            $language = $this->modx->lexicon->getLanguageNativeName($key);
        });

        $ml = $this->modx->sanitizeString($this->modx->getOption('manager_language', $_REQUEST));
        if (!$ml || !in_array($ml, $languages)) {
            $ml = $this->modx->getOption('manager_language', $_SESSION);
            if (!$ml) {
                // Try to detect default browser language
                $accept_languages = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
                preg_match_all('#([\w-]+)(?:[^,\d]+([\d.]+))?#', $accept_languages, $matches, PREG_SET_ORDER);
                foreach ($matches as $match) {
                    $lang = trim(explode('-', $match[1])[0]);
                    if (in_array($lang, $languages)) {
                        $ml = $lang;
                        break;
                    }
                }
            }
        }
        // Fall back to default language
        if (empty($ml)) {
            $ml = 'en';
        }
        // Save manager language to session
        $_SESSION['manager_language'] = $ml;
        // If user tried to change language - make redirect to hide it from url
        if (!empty($_GET['manager_language'])) {
            unset($_GET['manager_language']);
            $url = MODX_MANAGER_URL;
            if (!empty($_GET)) {
                $url .= '?' . urldecode(http_build_query($_GET));
            }
            $this->modx->sendRedirect($url);
        }
        // Set placeholders and load lexicons
        $this->modx->setOption('cultureKey', $ml);
        $this->modx->lexicon->load('core:login');

        $this->setPlaceholder('cultureKey', $ml);
        $this->setPlaceholder('languages', array_filter($showing));

        return $ml;
    }

    public function checkForAllowManagerForgotPassword() {
        $allow = $this->modx->getOption('allow_manager_login_forgot_password',null,true);
        if ($allow) {
            $this->setPlaceholder('allow_forgot_password',true);
        }
    }

    /**
     * Handle the forgot login hash, if existent
     *
     * @return void
     */
    public function handleForgotLoginHash()
    {
        // Legacy workaround
        if (!empty($_GET['modahsh'])) {
            $_GET['modhash'] = $_GET['modahsh'];
        }

        // Handle new password form
        if (!empty($_GET['modhash'])) {
            $hash = $this->modx->sanitizeString($_GET['modhash']);
            /** @var modDbRegister $registry */
            $registry = $this->modx->getService('registry', 'registry.modRegistry')
                ->getRegister('user', 'registry.modDbRegister');
            $registry->connect();
            $registry->subscribe('/pwd/change/' . $hash);
            if (!empty($registry->read(['poll_limit' => 1, 'remove_read' => false]))) {
                $this->scriptProperties['modhash'] = $this->modx->sanitizeString($hash);
                // Reassign lexicons to smarty so we could use system setting here
                $this->placeholders['_lang']['login_new_password_note'] = $this->modx->lexicon('login_new_password_note', [
                    'length' =>$this->modx->getOption('password_min_length')
                ]);
                $this->modx->smarty->assign('_lang', $this->placeholders['_lang']);
            } else {
                $this->modx->smarty->assign('error_message', $this->modx->lexicon('login_activation_key_err'));
            }
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
     * Handle when a user attempts to log in or change password
     *
     * @return void
     */
    public function handleLogin()
    {
        $hash = $this->modx->sanitizeString($this->scriptProperties['modhash']);
        if (!empty($hash)) {
            /** @var modDbRegister $registry */
            $registry = $this->modx->getService('registry', 'registry.modRegistry')
                ->getRegister('user', 'registry.modDbRegister');
            $registry->connect();
            $registry->subscribe('/pwd/change/' . $hash);
            $record = $registry->read(['poll_limit' => 1, 'remove_read' => false]);
            /** @var modUser $user */
            if (empty($record) || !$user = $this->modx->getObject('modUser', ['username' => reset($record)])) {
                $this->modx->smarty->assign('error_message', $this->modx->lexicon('login_activation_key_err'));

                return;
            }
            /** @var modUserProfile $profile */
            $profile = $user->getOne('Profile');
            $this->scriptProperties['passwordgenmethod'] = 's';
            $this->scriptProperties['passwordnotifymethod'] = 'no';
            $this->scriptProperties['newPassword'] = true;
            $this->modx->lexicon->load('core:user');

            if (!class_exists('modUserUpdateProcessor')) {
                require(MODX_CORE_PATH . 'model/modx/processors/security/user/update.class.php');
            }
            $processor = new modUserUpdateProcessor($this->modx, $this->scriptProperties);
            $processor->modx->error->reset();
            if (!class_exists('modUserValidation')) {
                require(MODX_CORE_PATH . 'model/modx/processors/security/user/_validation.php');
            }
            $validator = new modUserValidation($processor, $user, $profile);
            $password = $validator->checkPassword();
            if ($processor->hasErrors()) {
                $error = reset($processor->modx->error->getErrors(true))['msg'];
                $this->modx->smarty->assign('error_message', $error);

                return;
            }
            $user->set('password', $password);
            $user->save();

            $this->scriptProperties['username'] = $user->get('username');
            $this->scriptProperties['password'] = $password;
            $registry->read(['poll_limit' => 1, 'remove_read' => true]);
        }

        /** @var modProcessorResponse $response */
        $response = $this->modx->runProcessor('security/login', $this->scriptProperties);
        if (($response instanceof modProcessorResponse)) {
            if (!$response->isError()) {
                $url = !empty($this->scriptProperties['returnUrl'])
                    ? $this->scriptProperties['returnUrl']
                    : $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
                $url = $this->modx->getOption('url_scheme', null, MODX_URL_SCHEME) .
                    $this->modx->getOption('http_host', null, MODX_HTTP_HOST) . rtrim($url, '/');
                $this->modx->sendRedirect($url);
            } else {
                $errors = $response->getAllErrors();
                $error_message = implode("\n", $errors);
                $this->setPlaceholder('error_message', $error_message);
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
        $c->select(['modUser.*', 'Profile.email', 'Profile.fullname']);
        $c->innerJoin('modUserProfile', 'Profile');
        $c->where([
            'modUser.username' => $this->scriptProperties['username_reset'],
            'OR:Profile.email:=' => $this->scriptProperties['username_reset'],
        ]);
        /** @var modUser $user */
        $user = $this->modx->getObject('modUser', $c);
        if ($user) {
            $activationHash = md5(uniqid(md5($user->get('email') . '/' . $user->get('id')), true));

            /** @var modRegistry $registry */
            $registry = $this->modx->getService('registry', 'registry.modRegistry');
            /** @var modRegister $register */
            $register = $registry->getRegister('user', 'registry.modDbRegister');
            $register->connect();
            $register->subscribe('/pwd/change/');
            $register->send('/pwd/change/', [$activationHash => $user->get('username')], ['ttl' => 86400]);

            // Send activation email
            $message = $this->modx->lexicon('login_forgot_email');
            $placeholders = array_merge($this->modx->config, $user->toArray());
            $placeholders['hash'] = $activationHash;
            // Store previous placeholders
            $ph = $this->modx->placeholders;
            // now set those useful for modParser
            $this->modx->setPlaceholders($placeholders);
            $this->modx->getParser()->processElementTags('', $message, true, false, '[[', ']]', [], 10);
            $this->modx->getParser()->processElementTags('', $message, true, true, '[[', ']]', [], 10);
            // Then restore previous placeholders to prevent any breakage
            $this->modx->placeholders = $ph;

            $this->modx->smarty->assign('config', $this->modx->config);
            $this->modx->smarty->assign('content', $message);
            $message = $this->modx->smarty->fetch('email/default.tpl');
            $sent = $user->sendEmail($message, [
                'from' => $this->modx->getOption('emailsender'),
                'fromName' => $this->modx->getOption('site_name'),
                'sender' => $this->modx->getOption('emailsender'),
                'subject' => $this->modx->lexicon('login_email_subject'),
                'html' => true,
            ]);
            if (!$sent) {
                $this->setPlaceholder('error_message', $this->modx->lexicon('error_sending_email_to'));
            } else {
                $this->setPlaceholder('success_message', $this->modx->lexicon('login_password_reset_act_sent'));
            }
        } else {
            $this->setPlaceholder('success_message',$this->modx->lexicon('login_user_err_nf_email'));
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

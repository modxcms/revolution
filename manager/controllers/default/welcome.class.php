<?php
/**
 * Loads the welcome page
 *
 * @package modx
 * @subpackage manager.controllers
 */
 class WelcomeManagerController extends modManagerController {
    public $displayConfigScreen = false;
    public $showWelcomeScreen = false;
    public $rss;
    
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('home');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {

        $hasViewDocument = $this->modx->hasPermission('view_document');
        $hasViewUser = $this->modx->hasPermission('view_user');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/modx.panel.welcome.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.user.recent.resource.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/welcome.js');
        $this->addHtml('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.hasViewDocument = "'.($hasViewDocument ? 1 : 0).'";
            MODx.hasViewUser = "'.($hasViewUser ? 1 : 0).'";
            MODx.load({
                xtype: "modx-page-welcome"
                ,site_name: "'.htmlentities($this->modx->getOption('site_name')).'"
                ,displayConfigCheck: '.($this->displayConfigScreen ? 'true': 'false').'
                ,user: "'.$this->modx->user->get('id').'"
                ,newsEnabled: "'.$this->modx->getOption('feed_modx_news_enabled',null,true).'"
                ,securityEnabled: "'.$this->modx->getOption('feed_modx_security_enabled',null,true).'"
            });
        });
        // ]]>
        </script>');
        if ($this->showWelcomeScreen) {
            $url = $this->modx->getOption('welcome_screen_url',null,'http://misc.modx.com/revolution/welcome.20.html');
            $this->addHtml('<script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() { MODx.loadWelcomePanel("'.$url.'"); });
        // ]]>
        </script>');
        }
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return array
     */
    public function process(array $scriptProperties = array()) {
        $placeholders = array();
        
        /* assign current time message */
        $placeholders['online_users_msg'] = $this->modx->lexicon('onlineusers_message',array(
            'curtime' => strftime('%X', time()+$this->modx->getOption('server_offset_time',null,0))
        ));
        
        $placeholders['config_check_results'] = $this->configCheck();
        $placeholders['previous_login'] = $this->getPriorLogin();
        $placeholders['ausers'] = $this->getOnlineUsers();

        /* grab rss feeds */
        $this->modx->loadClass('xmlrss.modRSSParser','',false,true);
        $this->rss = new modRSSParser($this->modx);
        $placeholders['securefeed'] = $this->loadSecurityFeed();
        $placeholders['newsfeed'] = $this->loadNewsFeed();
        
        $this->checkForWelcomeScreen();

        return $placeholders;
    }

    public function configCheck() {
        $o = '';
        /* do some config checks */
        $modx =& $this->modx;
        $success = include_once $this->modx->getOption('processors_path') . 'system/config_check.inc.php';
        if (!$success) {
            $this->displayConfigScreen = true;
            $o = $config_check_results;
        } else {
            $this->displayConfigScreen = false;
        }

        return $o;
    }

    public function getPriorLogin() {
        $serverOffset = $this->modx->getOption('server_offset_time',null,0);
        $profile = $this->modx->user->getOne('Profile');
        if ($profile && $profile->get('lastlogin') != '') {
            $offset = $serverOffset * 60 * 60;
            $o = strftime('%b %d, %Y %I:%M %p',$profile->get('lastlogin')+$offset);
        } else {
            $o = $this->modx->lexicon('not_set');
        }
        return $o;
    }

    /**
     * @todo refactor online user tracking
     * @return array
     */
    public function getOnlineUsers() {
        $timetocheck = (time()-(60*20))+$this->modx->getOption('server_offset_time',null,0);
        $c = $this->modx->newQuery('modActiveUser');
        $c->where(array('lasthit:>' => $timetocheck));
        $c->sortby($this->modx->getSelectColumns('modActiveUser','modActiveUser','',array('username')),'ASC');
        $ausers = $this->modx->getCollection('modActiveUser',$c);
        $modx =& $this->modx;
        include_once $this->modx->getOption('processors_path'). 'system/actionlist.inc.php';
        foreach ($ausers as $user) {
            $currentaction = getAction($user->get('action'), $user->get('id'));
            $user->set('currentaction',$currentaction);
            $user->set('lastseen',strftime('%X',$user->lasthit+$serverOffset));
        }
        return $ausers;
    }

    public function loadNewsFeed() {
        $o = array();
        $url = $this->modx->getOption('feed_modx_news');
        $newsEnabled = $this->modx->getOption('feed_modx_news_enabled',null,true);
        if (!empty($url) && !empty($newsEnabled)) {
            $rss = $this->rss->parse($url);
            foreach (array_keys($rss->items) as $key) {
                $item= &$rss->items[$key];
                $item['pubdate'] = strftime('%c',$item['date_timestamp']);
            }
            $o = $rss->items;
        }
        return $o;
    }

    public function loadSecurityFeed() {
        $o = array();
        $url = $this->modx->getOption('feed_modx_security');
        $securityEnabled = $this->modx->getOption('feed_modx_security_enabled',null,true);
        if (!empty($url) && !empty($securityEnabled)) {
            $rss = $this->rss->parse($url);
            foreach (array_keys($rss->items) as $key) {
                $item= &$rss->items[$key];
                $item['pubdate'] = strftime('%c',$item['date_timestamp']);
            }
            $o = $rss->items;
        }
        return $o;
    }

    public function checkForWelcomeScreen() {
        if ($this->modx->getOption('welcome_screen',null,false)) {
            $this->showWelcomeScreen = true;
            $setting = $this->modx->getObject('modSystemSetting','welcome_screen');
            if ($setting) {
                $setting->set('value',false);
                $setting->save();
            }
            $setting = $this->modx->getObject('modUserSetting',array(
                'key' => 'welcome_screen',
                'user' => $this->modx->user->get('id'),
            ));
            if ($setting) {
                $setting->set('value',false);
                $setting->save();
            }
            $this->modx->reloadConfig();
        }
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('dashboard');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'welcome.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('welcome','configcheck');
    }
}
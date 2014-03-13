<?php
/**
 * Loads the welcome page
 *
 * @package modx
 * @subpackage manager.controllers
 */
 class WelcomeManagerController extends modManagerController {
    /**
     * Whether or not to show the welcome screen
     * @var boolean $showWelcomeScreen
     */
    public $showWelcomeScreen = false;

    /**
     * The current, active dashboard for the user
     * @var null|modDashboard $dashboard
     */
    public $dashboard = null;

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
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/modx.panel.welcome.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/welcome.js');
        $this->addHtml('<script type="text/javascript">
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-welcome"
        ,dashboard: '.$this->modx->toJSON($this->dashboard->toArray()).'
    });
});
</script>');
        if ($this->showWelcomeScreen) {
            $url = $this->modx->getOption('welcome_screen_url',null,'http://misc.modx.com/revolution/welcome.20.html');
            $this->addHtml('<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() { MODx.loadWelcomePanel("'.$url.'"); });
// ]]></script>');
        }
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return array
     */
    public function process(array $scriptProperties = array()) {
        $placeholders = array();

        $this->checkForWelcomeScreen();

        $this->dashboard = $this->modx->user->getDashboard();
        $placeholders['dashboard'] = $this->dashboard->render($this);
        return $placeholders;
    }

    /**
     * Check to show if we need to show the Welcome Screen for the user
     * @return void
     */
    public function checkForWelcomeScreen() {
        if ($this->modx->getOption('welcome_screen',null,false)) {
            $this->showWelcomeScreen = true;
            /** @var modSystemSetting $setting */
            $setting = $this->modx->getObject('modSystemSetting','welcome_screen');
            if ($setting) {
                $setting->set('value',false);
                $setting->save();
            }
            /** @var modUserSetting $setting */
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

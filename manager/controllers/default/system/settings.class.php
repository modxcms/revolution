<?php
/**
 * Loads the system settings page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemSettingsManagerController extends modManagerController {
    public $onSiteSettingsRender = '';
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('settings');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addHtml('<script type="text/javascript">
        // <[!CDATA[
        Ext.onReady(function() {
            MODx.add("modx-page-system-settings");
        });
        MODx.onSiteSettingsRender = "'.$this->onSiteSettingsRender.'";
        // ]]>
        </script>');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.settings.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.system.settings.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/system/settings.js');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $onSiteSettingsRender = $this->modx->invokeEvent('OnSiteSettingsRender');
        if (is_array($onSiteSettingsRender)) {
            $this->onSiteSettingsRender = implode("\n",$onSiteSettingsRender);
        }
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('system_settings');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'system/settings/index.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('setting');
    }
}
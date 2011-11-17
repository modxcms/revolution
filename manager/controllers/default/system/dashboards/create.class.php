<?php
/**
 * @package modx
 * @subpackage manager.controllers
 */
/**
 * Loads the dashboard create page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemDashboardsCreateManagerController extends modManagerController {

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('dashboards');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     *
     * @param array $scriptProperties
     * @return array
     */
    public function process(array $scriptProperties = array()) {
        return array();

    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->getOption('manager_url')."assets/modext/widgets/system/modx.panel.dashboard.js");
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/system/dashboards/create.js');
        $this->addHtml('<script type="text/javascript">Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-dashboard-create"
    });
});</script>');
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('dashboards');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'system/dashboards/create.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('dashboards','user');
    }
}
<?php
/**
 * @package modx
 * @subpackage manager.controllers
 */
/**
 * Loads the dashboard management page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemDashboardsManagerController extends modManagerController {
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
        $placeholders = array();
        return $placeholders;

    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->getOption('manager_url')."assets/modext/widgets/system/modx.grid.dashboard.widgets.js");
        $this->addJavascript($this->modx->getOption('manager_url')."assets/modext/widgets/system/modx.panel.dashboards.js");
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/system/dashboards/list.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-dashboards"
            });
        });
        </script>');
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
        return 'system/dashboards/index.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('dashboards');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Dashboards';
    }
}
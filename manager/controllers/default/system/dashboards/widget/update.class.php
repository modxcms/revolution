<?php
/**
 * Loads the dashboard update page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemDashboardsWidgetUpdateManagerController extends modManagerController {
    /** @var modDashboard $dashboard */
    public $dashboard;
    /** @var modDashboardWidget $widget */
    public $widget;
    /** @var array $widgetArray */
    public $widgetArray = array();

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
        if (empty($this->scriptProperties['id'])) return $this->failure($this->modx->lexicon('widget_err_ns'));
        $this->widget = $this->modx->getObject('modDashboardWidget',$this->scriptProperties['id']);
        if (empty($this->widget)) return $this->failure($this->modx->lexicon('widget_err_nf'));
        
        $this->dashboard = $this->widget->getOne('Dashboard');
        $this->widgetArray = $this->widget->toArray();

        return $this->widgetArray;

    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->getOption('manager_url')."assets/modext/widgets/system/modx.panel.dashboard.widget.js");
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/system/dashboards/widget/update.js');
        $this->addHtml('<script type="text/javascript">Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-dashboard-widget-update"
        ,record: '.$this->modx->toJSON($this->widgetArray).'
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
        return 'system/dashboards/widget/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('dashboards','user');
    }
}
<?php
/**
 * Loads the dashboard update page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemDashboardsUpdateManagerController extends modManagerController {
    /** @var modDashboard $dashboard */
    public $dashboard;
    /** @var array $dashboardArray */
    public $dashboardArray;

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
        if (empty($this->scriptProperties['id'])) return $this->failure($this->modx->lexicon('dashboard_err_ns'));
        $this->dashboard = $this->modx->getObject('modDashboard',$this->scriptProperties['id']);
        if (empty($this->dashboard)) return $this->failure($this->modx->lexicon('dashboard_err_nf'));
        
        $this->dashboardArray = $this->dashboard->toArray();
        //$this->dashboardArray['usergroups'] = $this->getUserGroups();
        $this->dashboardArray['widgets'] = $this->getWidgets();

        return $this->dashboardArray;

    }

    /**
     * Get all the Widgets placed on this Dashboard
     * @return array
     */
    public function getWidgets() {
        $c = $this->modx->newQuery('modDashboardWidgetPlacement');
        $c->innerJoin('modDashboardWidget','Widget');
        $c->where(array(
            'dashboard' => $this->dashboard->get('id'),
        ));
        $c->sortby('rank','ASC');
        $c->select($this->modx->getSelectColumns('modDashboardWidgetPlacement','modDashboardWidgetPlacement'));
        $c->select(array(
            'Widget.name',
            'Widget.description',
            'Widget.lexicon',
        ));
        $widgets = $this->modx->getCollection('modDashboardWidgetPlacement',$c);
        $list = array();
        /** @var modDashboardWidgetPlacement $widget */
        foreach ($widgets as $widget) {
            if ($widget->get('lexicon') != 'core:dashboards') {
                $this->modx->lexicon->load($widget->get('lexicon'));
            }
            $list[] = array(
                $widget->get('dashboard'),
                $widget->get('widget'),
                $widget->get('rank'),
                $widget->get('name'),
                $widget->get('description'),
                $this->modx->lexicon($widget->get('description')),
            );
        }
        return $list;
    }

    /**
     * Get all the User Groups assigned to this Dashboard
     * @return array
     */
    public function getUserGroups() {
        $c = $this->modx->newQuery('modUserGroup');
        $c->where(array(
            'dashboard' => $this->dashboard->get('id'),
        ));
        $c->sortby('name','ASC');
        $usergroups = $this->modx->getCollection('modUserGroup',$c);
        $list = array();
        /** @var modUserGroup $usergroup */
        foreach ($usergroups as $usergroup) {
            $list[] = array($usergroup->get('id'),$usergroup->get('name'));
        }
        return $list;
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->getOption('manager_url')."assets/modext/widgets/system/modx.panel.dashboard.js");
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/system/dashboards/update.js');
        $this->addHtml('<script type="text/javascript">Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-dashboard-update"
        ,record: '.$this->modx->toJSON($this->dashboardArray).'
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
        return 'system/dashboards/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('dashboards','user');
    }
}
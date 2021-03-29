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
     * Get the active widget
     * @return void
     */
    public function initialize() {
        if (!empty($this->scriptProperties['id']) && strlen($this->scriptProperties['id']) === strlen((integer)$this->scriptProperties['id'])) {
            $this->widget = $this->modx->getObject('modDashboardWidget', array('id' => $this->scriptProperties['id']));
        }
    }

    /**
     * Custom logic code here for setting placeholders, etc
     *
     * @param array $scriptProperties
     * @return array
     */
    public function process(array $scriptProperties = array()) {
        if (empty($this->widget)) return $this->failure($this->modx->lexicon('widget_err_nf'));
        $this->widgetArray = $this->widget->toArray();
        $this->widgetArray['dashboards'] = $this->getDashboards();

        return $this->widgetArray;
    }

    /**
     * Get the Dashboards this Widget has been placed on
     * @return array
     */
    public function getDashboards() {
        $list = array();
        $c = $this->modx->newQuery('modDashboardWidgetPlacement');
        $c->innerJoin('modDashboard','Dashboard');
        $c->where(array(
            'widget' => $this->widget->get('id'),
        ));
        $c->sortby('Dashboard.name','ASC');
        $c->select($this->modx->getSelectColumns('modDashboardWidgetPlacement','modDashboardWidgetPlacement'));
        $c->select(array(
            'Dashboard.name',
            'Dashboard.description',
        ));
        $placements = $this->widget->getMany('Placements',$c);
        /** @var modDashboardWidgetPlacement $placement */
        foreach ($placements as $placement) {
            $list[] = array(
                $placement->get('dashboard'),
                $placement->get('name'),
                $placement->get('description'),
            );
        }
        return $list;
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl."assets/modext/widgets/system/modx.panel.dashboard.widget.js");
        $this->addJavascript($mgrUrl.'assets/modext/sections/system/dashboards/widget/update.js');
        $this->addHtml('<script>Ext.onReady(function() {
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
        return '';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        $topics = array('dashboards','user');
        if ($this->widget) {
            $lexicon = $this->widget->get('lexicon');
            if (!empty($lexicon) && $lexicon != 'core:dashboards') {
                $topics[] = $lexicon;
            }
        }
        return $topics;
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Dashboard+Widgets';
    }
}

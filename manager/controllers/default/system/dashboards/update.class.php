<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modDashboard;
use MODX\Revolution\modDashboardWidget;
use MODX\Revolution\modDashboardWidgetPlacement;
use MODX\Revolution\modManagerController;
use MODX\Revolution\modUserGroup;

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
     * @param array $scriptProperties
     *
     * @return array
     */
    public function process(array $scriptProperties = []) {
        if (empty($this->scriptProperties['id']) || strlen($this->scriptProperties['id']) !== strlen((integer)$this->scriptProperties['id'])) {
            $this->failure($this->modx->lexicon('dashboard_err_ns'));
            return [];
        }
        $this->dashboard = $this->modx->getObject(modDashboard::class, ['id' => $this->scriptProperties['id']]);
        if (empty($this->dashboard)) {
            $this->failure($this->modx->lexicon('dashboard_err_nf'));
            return [];
        }

        $this->dashboardArray = $this->dashboard->toArray();
        $this->dashboardArray['widgets'] = $this->getWidgets();

        return $this->dashboardArray;

    }

    /**
     * Get all the Widgets placed on this Dashboard
     * @return array
     */
    public function getWidgets() {
        $c = $this->modx->newQuery(modDashboardWidgetPlacement::class);
        $c->where([
            'dashboard' => $this->dashboard->get('id'),
            'user' => 0,
        ]);
        $c->sortby('modDashboardWidgetPlacement.rank','ASC');
        $placements = $this->modx->getCollection(modDashboardWidgetPlacement::class, $c);
        $list = [];
        /** @var modDashboardWidgetPlacement $placement */
        foreach ($placements as $placement) {
            $placement->getOne('Widget');

            if (!($placement->Widget instanceof modDashboardWidget)) {
                continue;
            }

            if ($placement->Widget->get('lexicon') != 'core:dashboards') {
                $this->modx->lexicon->load($placement->Widget->get('lexicon'));
            }
            $widgetArray = $placement->Widget->toArray();
            $list[] = [
                $placement->get('dashboard'),
                $placement->get('widget'),
                $placement->get('rank'),
                $widgetArray['name'],
                $widgetArray['name_trans'],
                $widgetArray['description'],
                $widgetArray['description_trans'],
            ];
        }
        return $list;
    }

    /**
     * Get all the User Groups assigned to this Dashboard
     * @return array
     */
    public function getUserGroups() {
        $list = [];
        $c = $this->modx->newQuery(modUserGroup::class);
        $c->where([
            'dashboard' => $this->dashboard->get('id'),
        ]);
        $c->sortby('name', 'ASC');
        $usergroups = $this->modx->getIterator(modUserGroup::class, $c);
        /** @var modUserGroup $usergroup */
        foreach ($usergroups as $usergroup) {
            $list[] = [$usergroup->get('id'), $usergroup->get('name')];
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
        $data = json_encode([
            'xtype' => 'modx-page-dashboard-update',
            'record' => $this->dashboardArray,
        ], JSON_INVALID_UTF8_SUBSTITUTE);
        $this->addHtml('<script>Ext.onReady(function(){MODx.load(' . $data . ')});</script>');
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('dashboards').': '.$this->dashboardArray['name'];
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
        return ['dashboards','user'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Dashboards';
    }
}

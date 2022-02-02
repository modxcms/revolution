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
    public $widgetArray = [];

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
            $this->widget = $this->modx->getObject(modDashboardWidget::class, ['id' => $this->scriptProperties['id']]);
        }
    }

    /**
     * Custom logic code here for setting placeholders, etc
     *
     * @param array $scriptProperties
     * @return array
     */
    public function process(array $scriptProperties = []) {
        if (empty($this->widget)) {
            return $this->failure($this->modx->lexicon('widget_err_nf'));
        }
        $this->widgetArray = $this->widget->toArray();
        $this->widgetArray['dashboards'] = $this->getDashboards();

        return $this->widgetArray;
    }


    /**
     * Get the Dashboards this Widget has been placed on
     *
     * @param int $user
     *
     * @return array
     */
    public function getDashboards($user = 0) {
        $list = [];
        $c = $this->modx->newQuery(modDashboardWidgetPlacement::class);
        $c->innerJoin(modDashboard::class, 'Dashboard');
        $c->where([
            'widget' => $this->widget->get('id'),
            'user' => $user,
        ]);
        $c->sortby('Dashboard.name','ASC');
        $c->select($this->modx->getSelectColumns(modDashboardWidgetPlacement::class, 'modDashboardWidgetPlacement'));
        $c->select([
            'Dashboard.name',
            'Dashboard.description',
        ]);
        $placements = $this->widget->getMany('Placements',$c);
        /** @var modDashboardWidgetPlacement $placement */
        foreach ($placements as $placement) {
            $list[] = [
                $placement->get('dashboard'),
                $placement->get('name'),
                $placement->get('description'),
            ];
        }
        return $list;
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/core/modx.orm.js');
        $this->addJavascript($mgrUrl . "assets/modext/widgets/system/modx.panel.dashboard.widget.js");
        $this->addJavascript($mgrUrl . 'assets/modext/sections/system/dashboards/widget/update.js');

        $this->widgetArray['properties'] = $this->_parseCustomData($this->widget->get('properties'));
        $data = [
            'xtype' => 'modx-page-dashboard-widget-update',
            'record' => $this->widgetArray,
        ];

        $this->addHtml('<script>Ext.onReady(function() {MODx.load(' . json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE) . ');});</script>');
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('widget').': '.$this->widgetArray['name'];
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
        $topics = ['dashboards','user'];
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


    /**
     * @param array $remoteData
     * @param string $path
     *
     * @return array
     */
    private function _parseCustomData($remoteData = [], $path = '')
    {
        if (!$remoteData) {
            return [];
        }
        $usemb = function_exists('mb_strlen') && (boolean)$this->modx->getOption('use_multibyte', null, false);
        $encoding = $this->modx->getOption('modx_charset', null, 'UTF-8');
        $fields = [];
        foreach ($remoteData as $key => $value) {
            $field = [
                'name' => $key,
                'id' => (!empty($path) ? $path . '.' : '') . $key,
            ];
            if (is_array($value)) {
                $field['iconCls'] = 'icon-folder';
                $field['text'] = $key;
                $field['leaf'] = false;
                $field['children'] = $this->_parseCustomData($value, $key);
            } else {
                $v = $value;
                if ($usemb) {
                    if (mb_strlen($v, $encoding) > 30) {
                        $v = mb_substr($v, 0, 30, $encoding) . '...';
                    }
                } elseif (strlen($v) > 30) {
                    $v = substr($v, 0, 30) . '...';
                }
                $field['iconCls'] = 'icon-terminal';
                $field['text'] = $key . ' - <i>' . htmlentities($v, ENT_QUOTES, $encoding) . '</i>';
                $field['leaf'] = true;
                $field['value'] = $value;
            }
            $fields[] = $field;
        }

        return $fields;
    }
}

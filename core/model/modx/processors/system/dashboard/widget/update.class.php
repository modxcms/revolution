<?php
/**
 * Updates a Dashboard Widget
 *
 * @param integer $id The ID of the dashboard widget
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.dashboard.widget
 */
class modDashboardWidgetUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modDashboardWidget';
    public $languageTopics = array('dashboards');
    public $permission = 'dashboards';
    public $objectType = 'widget';
}
return 'modDashboardWidgetUpdateProcessor';
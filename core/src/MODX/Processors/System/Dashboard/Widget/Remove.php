<?php

namespace MODX\Processors\System\Dashboard\Widget;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Removes a Dashboard Widget
 *
 * @param integer $id The ID of the dashboard widget
 *
 * @package modx
 * @subpackage processors.system.dashboard.widget
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modDashboardWidget';
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'widget';
}
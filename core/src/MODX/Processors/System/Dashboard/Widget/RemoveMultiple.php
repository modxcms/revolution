<?php

namespace MODX\Processors\System\Dashboard\Widget;

use MODX\Processors\modProcessor;
use MODX\modDashboardWidget;

/**
 * Removes multiple Dashboard Widgets
 *
 * @package modx
 * @subpackage processors.system.dashboard.widget
 */
class RemoveMultiple extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('dashboards');
    }


    public function getLanguageTopics()
    {
        return ['dashboards'];
    }


    public function process()
    {
        $widgets = $this->getProperty('widgets', null);

        if (empty($widgets)) {
            return $this->failure($this->modx->lexicon('widget_err_ns'));
        }

        $widgetIds = is_array($widgets) ? $widgets : explode(',', $widgets);
        foreach ($widgetIds as $widgetId) {
            /** @var modDashboardWidget $widget */
            $widget = $this->modx->getObject('modDashboardWidget', $widgetId);
            if (empty($widget)) {
                continue;
            }

            if ($widget->remove() == false) {
                $this->modx->log(\MODX::LOG_LEVEL_ERROR, $this->modx->lexicon('widget_err_remove'));
                continue;
            }
            $this->modx->logManagerAction('dashboard_widget_remove', 'modDashboardWidget', $widget->get('id'));
        }

        return $this->success();
    }
}

return 'modDashboardWidgetRemoveMultipleProcessor';
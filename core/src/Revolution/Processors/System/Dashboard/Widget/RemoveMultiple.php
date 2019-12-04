<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Dashboard\Widget;

use MODX\Revolution\modDashboardWidget;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modX;

/**
 * Removes multiple Dashboard Widgets
 * @package MODX\Revolution\Processors\System\Dashboard\Widget
 */
class RemoveMultiple extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('dashboards');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['dashboards'];
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $widgets = $this->getProperty('widgets');

        if (empty($widgets)) {
            return $this->failure($this->modx->lexicon('widget_err_ns'));
        }

        $widgetIds = is_array($widgets) ? $widgets : explode(',', $widgets);
        foreach ($widgetIds as $widgetId) {
            /** @var modDashboardWidget $widget */
            $widget = $this->modx->getObject(modDashboardWidget::class, $widgetId);
            if ($widget === null) {
                continue;
            }

            if ($widget->remove() === false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('widget_err_remove'));
                continue;
            }
            $this->modx->logManagerAction('dashboard_widget_remove', modDashboardWidget::class, $widget->get('id'));
        }

        return $this->success();
    }
}

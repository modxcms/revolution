<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Dashboard;

use MODX\Revolution\modDashboard;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modX;

/**
 * Removes multiple Dashboards
 * @package MODX\Revolution\Processors\System\Dashboard
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
        $dashboards = $this->getProperty('dashboards');

        if (empty($dashboards)) {
            return $this->failure($this->modx->lexicon('dashboard_err_ns'));
        }

        $dashboardIds = is_array($dashboards) ? $dashboards : explode(',', $dashboards);
        foreach ($dashboardIds as $dashboardId) {
            /** @var modDashboard $dashboard */
            $dashboard = $this->modx->getObject(modDashboard::class, $dashboardId);
            if ($dashboard === null) {
                continue;
            }

            if ($dashboard->remove() === false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('dashboard_err_remove'));
                continue;
            }
            $this->modx->logManagerAction('dashboard_remove', modDashboard::class, $dashboard->get('id'));
        }

        return $this->success();
    }
}

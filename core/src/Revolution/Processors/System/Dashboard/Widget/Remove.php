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
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Removes a Dashboard Widget
 * @param integer $id The ID of the dashboard widget
 * @package MODX\Revolution\Processors\System\Dashboard\Widget
 */
class Remove extends RemoveProcessor
{
    public $classKey = modDashboardWidget::class;
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'widget';
}

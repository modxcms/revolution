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
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Updates a Dashboard Widget
 * @param integer $id The ID of the dashboard widget
 * @package MODX\Revolution\Processors\System\Dashboard\Widget
 */
class Update extends UpdateProcessor
{
    public $classKey = modDashboardWidget::class;
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'widget';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        if ($properties = $this->getProperty('properties')) {
            if (is_string($properties)) {
                $properties = json_decode($properties, true);
            }
            $this->setProperty('properties', $properties);
        }

        return parent::beforeSet();
    }

}

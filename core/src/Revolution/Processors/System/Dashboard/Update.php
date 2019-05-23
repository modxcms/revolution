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
use MODX\Revolution\modDashboardWidgetPlacement;
use MODX\Revolution\modObjectUpdateProcessor;

/**
 * Updates a Dashboard
 * @param integer $id The ID of the dashboard
 * @package MODX\Revolution\Processors\System\Dashboard
 */
class Update extends modObjectUpdateProcessor
{
    public $classKey = modDashboard::class;
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'dashboard';

    /** @var modDashboard $object */
    public $object;

    /**
     * @return bool
     */
    public function afterSave()
    {
        $this->setWidgets();

        return parent::afterSave();
    }

    /**
     * Set the widgets assigned to this Dashboard
     */
    public function setWidgets()
    {
        if ($widgets = $this->getProperty('widgets')) {
            if (!is_array($widgets)) {
                $widgets = json_decode($widgets, true);
            }
            $this->modx->removeCollection(modDashboardWidgetPlacement::class, [
                'dashboard' => $this->object->get('id'),
                'user' => 0,
            ]);
            foreach ($widgets as $data) {
                $key = [
                    'dashboard' => $this->object->get('id'),
                    'user' => 0,
                    'widget' => $data['widget'],
                ];
                if (!$widget = $this->modx->getObject(modDashboardWidgetPlacement::class, $key)) {
                    /** @var modDashboardWidgetPlacement $widget */
                    $widget = $this->modx->newObject(modDashboardWidgetPlacement::class);
                    $widget->fromArray($key, '', true, true);
                }
                $widget->set('rank', $data['rank']);
                $widget->save();
            }

            $this->object->sortWidgets();
        }
    }
}

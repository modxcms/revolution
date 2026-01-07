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
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Updates a Dashboard
 * @param integer $id The ID of the dashboard
 * @package MODX\Revolution\Processors\System\Dashboard
 */
class Update extends UpdateProcessor
{
    public $classKey = modDashboard::class;
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'dashboard';

    /** @var modDashboard $object */
    public $object;

    public function beforeSave()
    {
        /* validate name field */
        $name = $this->object->get('name');
        $id = $this->object->get('id');

        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('dashboard_err_ns_name'));
        } elseif ($this->alreadyExists($name, $id)) {
            $this->addFieldError('name', $this->modx->lexicon('dashboard_err_ae_name', [
                'name' => $name,
            ]));
        }

        return parent::beforeSave();
    }

    /**
     * Check to see if a Dashboard with the specified name already exists
     * @param string $name
     * @return boolean
     */
    public function alreadyExists($name, $id)
    {
        return $this->modx->getCount(modDashboard::class, [
            'name' => $name,
            'id:!=' => $id
        ]) > 0;
    }

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
        /** @var modDashboardWidgetPlacement[] $previousWidgets */
        $previousWidgets = $this->modx->getCollection(modDashboardWidgetPlacement::class, [
            'dashboard' => $this->object->id,
            'user' => 0
        ]);
        $previousWidgets = array_map(function ($item) {
            return $item->widget;
        }, $previousWidgets);

        $newWidgets = [];

        $widgets = $this->getProperty('widgets');
        if ($widgets) {
            if (!is_array($widgets)) {
                $widgets = json_decode($widgets, true);
            }
            $this->modx->removeCollection(modDashboardWidgetPlacement::class, [
                'dashboard' => $this->object->get('id'),
                'user' => 0,
            ]);
            foreach ($widgets as $data) {
                $newWidgets[] = $data['widget'];
                $key = [
                    'dashboard' => $this->object->get('id'),
                    'user' => 0,
                    'widget' => $data['widget'],
                ];

                /** @var modDashboardWidgetPlacement $widget */
                $widget = $this->modx->getObject(modDashboardWidgetPlacement::class, $key);
                if (!$widget) {
                    $widget = $this->modx->newObject(modDashboardWidgetPlacement::class);
                    $widget->fromArray($key, '', true, true);
                }
                $widget->set('rank', $data['rank']);
                $widget->save();
            }
            $addedWidgets = array_values(array_diff($newWidgets, $previousWidgets));
            $removedWidgets = array_values(array_diff($previousWidgets, $newWidgets));

            if (!empty($addedWidgets)) {
                $userDashboardsQuery = $this->modx->newQuery(modDashboardWidgetPlacement::class, ['dashboard' => $this->object->id, 'user:!=' => 0]);
                $userDashboardsQuery->distinct(true);
                $userDashboardsQuery->select('user');
                $userDashboardsQuery->prepare();
                $userDashboardsQuery->stmt->execute();
                $userDashboards = $userDashboardsQuery->stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
                $userDashboards = array_map('intval', $userDashboards);

                foreach ($userDashboards as $user) {
                    foreach ($addedWidgets as $widgetId) {
                        $key = ['dashboard' => $this->object->id, 'user' => $user, 'widget' => $widgetId];

                        /** @var modDashboardWidgetPlacement $widgetPlacement */
                        $widgetPlacement = $this->modx->getObject(modDashboardWidgetPlacement::class, $key);
                        if (!$widgetPlacement) {
                            $widgetPlacement = $this->modx->newObject(modDashboardWidgetPlacement::class);
                            $widgetPlacement->fromArray($key, '', true, true);
                            $widgetPlacement->save();
                        }
                    }
                }
            }
            if (!empty($removedWidgets)) {
                $this->modx->removeCollection(modDashboardWidgetPlacement::class, ['dashboard' => $this->object->id, 'widget:IN' => $removedWidgets]);
            }
            $this->object->sortWidgets();
        }
    }
}

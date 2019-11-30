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
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Creates a Dashboard
 * @param integer $id The ID of the dashboard
 * @package MODX\Revolution\Processors\System\Dashboard
 */
class Create extends CreateProcessor
{
    public $classKey = modDashboard::class;
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'dashboard';

    /**
     * @return bool
     */
    public function beforeSave()
    {
        /* validate name field */
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('dashboard_err_ns_name'));

        } else if ($this->alreadyExists($name)) {
            $this->addFieldError('name', $this->modx->lexicon('dashboard_err_ae_name', [
                'name' => $name,
            ]));
        }

        return !$this->hasErrors();
    }

    /**
     * @return bool
     * @throws \xPDO\xPDOException
     */
    public function afterSave()
    {
        $widgets = $this->getProperty('widgets');
        if ($widgets !== null) {
            $this->assignWidgets($widgets);
        }
        return parent::afterSave();
    }

    /**
     * See if a Dashboard already exists with this name
     * @param string $name
     * @return bool
     */
    public function alreadyExists($name)
    {
        return $this->modx->getCount(modDashboard::class, ['name' => $name]) > 0;
    }

    /**
     * Assign widgets to this dashboard
     * @param array|string $widgets
     * @return array An array of placement objects
     * @throws \xPDO\xPDOException
     */
    public function assignWidgets($widgets)
    {
        $placements = [];

        /** @var array|string $widgets */
        $widgets = is_array($widgets) ? $widgets : $this->modx->fromJSON($widgets);

        /** @var array $widget */
        foreach ($widgets as $widget) {
            /** @var modDashboardWidgetPlacement $placement */
            $placement = $this->modx->newObject(modDashboardWidgetPlacement::class);
            $placement->set('dashboard', $this->object->get('id'));
            $placement->set('widget', $widget['widget']);
            $placement->set('rank', $widget['rank']);
            $placement->save();
            $placements[] = $placement;
        }
        return $placements;
    }
}

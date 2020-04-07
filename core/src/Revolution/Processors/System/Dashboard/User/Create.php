<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Dashboard\User;

use MODX\Revolution\modDashboard;
use MODX\Revolution\modDashboardWidget;
use MODX\Revolution\modDashboardWidgetPlacement;
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Class Create
 * @package MODX\Revolution\Processors\System\Dashboard\User
 */
class Create extends CreateProcessor
{
    public $classKey = modDashboardWidgetPlacement::class;
    public $languageTopics = ['dashboards'];

    /** @var modDashboardWidgetPlacement $object */
    public $object;

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->object->fromArray([
            'user' => (int)$this->modx->user->get('id'),
            'dashboard' => (int)$this->getProperty('dashboard'),
            'widget' => (int)$this->getProperty('widget'),
        ], '', true, true);

        return parent::beforeSet();
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        /** @var modDashboardWidget $widget */
        if (($widget = $this->object->getOne('Widget')) && ($permission = $widget->get('permission')) && !$this->modx->hasPermission($permission)) {
            return $this->modx->lexicon('access_denied');
        }
        $this->object->set('rank', $this->modx->getCount($this->classKey, [
            'user' => $this->object->get('user'),
            'dashboard' => $this->object->get('dashboard'),
        ]));

        return parent::beforeSave();
    }


    /**
     * @return bool
     */
    public function afterSave()
    {
        /** @var modDashboard $dashboard */
        if ($dashboard = $this->object->getOne('Dashboard')) {
            $dashboard->sortWidgets($this->modx->user->get('id'), true);
        }

        return parent::afterSave();
    }


    /**
     * Log event
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('user_widget_add', modDashboardWidget::class, $this->object->get('widget'));
    }
}

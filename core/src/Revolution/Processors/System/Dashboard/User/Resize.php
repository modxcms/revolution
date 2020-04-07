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
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Class Resize
 * @package MODX\Revolution\Processors\System\Dashboard\User
 */
class Resize extends UpdateProcessor
{
    public $classKey = modDashboardWidgetPlacement::class;
    public $languageTopics = ['dashboards'];

    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        $primaryKey = [
            'user' => (int)$this->modx->user->get('id'),
            'dashboard' => (int)$this->getProperty('dashboard'),
            'widget' => (int)$this->getProperty('widget'),
        ];

        if (!$this->modx->getCount(modDashboard::class, ['id' => $primaryKey['dashboard'], 'customizable' => true])) {
            return $this->modx->lexicon('access_denied');
        }

        if (!$this->object = $this->modx->getObject($this->classKey, $primaryKey)) {
            return $this->modx->lexicon($this->objectType . '_err_nfs');
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->setProperties([
            'size' => $this->getProperty('size'),
        ]);

        return parent::beforeSet();
    }

    /**
     * Log event
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('user_widget_resize', modDashboardWidget::class, $this->object->get('widget'));
    }
}

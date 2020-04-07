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
use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\Processors\ProcessorResponse;

/**
 * Class Remove
 * @package MODX\Revolution\Processors\System\Dashboard\User
 */
class Remove extends RemoveProcessor
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
     * @return array|mixed|string
     */
    public function process()
    {
        if ($this->removeObject() === false) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_remove'));
        }
        $this->afterRemove();
        $this->fireAfterRemoveEvent();
        $this->logManagerAction();

        $new_widgets = 0;
        $this->modx->error->reset();
        /** @var ProcessorResponse $res */
        $res = $this->modx->runProcessor(GetList::class, [
            'dashboard' => $this->object->get('dashboard'),
            'combo' => true,
        ]);
        if (!$res->isError()) {
            $tmp = $res->getResponse();
            if (is_string($tmp)) {
                $tmp = json_decode($tmp, true);
            }
            if (isset($tmp['total'])) {
                $new_widgets = $tmp['total'];
            }
        }

        return $this->success('', [
            'new_widgets' => $new_widgets,
        ]);
    }

    /**
     * @return bool
     */
    public function afterRemove()
    {
        /** @var modDashboard $dashboard */
        if ($dashboard = $this->object->getOne('Dashboard')) {
            $dashboard->sortWidgets($this->modx->user->get('id'), true);
        }

        return parent::afterRemove();
    }

    /**
     * Log event
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('user_widget_remove', modDashboardWidget::class, $this->object->get('widget'));
    }
}

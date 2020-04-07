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
 * Class Sort
 * @package MODX\Revolution\Processors\System\Dashboard\User
 */
class Sort extends UpdateProcessor
{
    public $classKey = modDashboardWidgetPlacement::class;
    public $languageTopics = ['dashboards'];

    /** @var modDashboardWidgetPlacement $object */
    public $object;

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
        $from = $this->getProperty('from');
        $to = $this->getProperty('to');

        $where = [
            'user' => $this->object->get('user'),
            'dashboard' => $this->object->get('dashboard'),
        ];

        if ($from < $to) {
            $where['rank:<='] = $to;
            if ($from !== 0) {
                $where[] = ["`{$this->modx->getAlias($this->classKey)}`.`rank` > {$from} AND `{$this->modx->getAlias($this->classKey)}`.`rank` > 0"];
            } else {
                $where['rank:>'] = $from;
            }

            $widgets = $this->modx->getIterator($this->classKey, $where);
            foreach ($widgets as $widget) {
                $widget->set('rank', $widget->get('rank') - 1);
                $widget->save();
            }
        } else {
            $where = array_merge($where, [
                'rank:>=' => $to,
                'rank:<' => $from,
            ]);
            $widgets = $this->modx->getIterator($this->classKey, $where);
            foreach ($widgets as $widget) {
                $widget->set('rank', $widget->get('rank') + 1);
                $widget->save();
            }
        }

        $this->object->set('rank', $to);
        $this->object->save();

        /** @var modDashboard $dashboard */
        if ($dashboard = $this->object->getOne('Dashboard')) {
            $dashboard->sortWidgets($this->object->get('user'));
        }

        return $this->success();
    }


    /**
     * Log event
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('user_widget_sort', modDashboardWidget::class, $this->object->get('widget'));
    }
}

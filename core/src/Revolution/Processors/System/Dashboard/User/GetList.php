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

use MODX\Revolution\modAccessibleObject;
use MODX\Revolution\modDashboardWidget;
use MODX\Revolution\modDashboardWidgetPlacement;
use MODX\Revolution\Processors\Model\GetListProcessor;
use PDO;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Class GetList
 * @package MODX\Revolution\Processors\System\Dashboard\User
 */
class GetList extends GetListProcessor
{
    public $classKey = modDashboardWidget::class;
    public $languageTopics = ['dashboards'];

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $q = $this->modx->newQuery(modDashboardWidgetPlacement::class, [
            'dashboard' => $this->getProperty('dashboard'),
            'user' => $this->modx->user->get('id'),
        ]);
        $q->select('widget');
        if ($q->prepare() && $q->stmt->execute() && $exists = $q->stmt->fetchAll(PDO::FETCH_COLUMN)) {
            $c->where(['id:NOT IN' => $exists]);
        }

        return $c;
    }


    /**
     * @param array $data
     * @return array
     */
    public function iterate(array $data)
    {
        $list = [];
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $object) {
            if ($object->get('permission') && !$this->modx->hasPermission($object->get('permission'))) {
                --$data['total'];
                continue;
            }
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);

        return $list;
    }

}

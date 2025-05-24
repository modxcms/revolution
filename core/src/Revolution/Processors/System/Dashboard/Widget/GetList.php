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
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of dashboards
 * @param string $username (optional) Will filter the grid by searching for this username
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\System\Dashboard\Widget
 */
class GetList extends GetListProcessor
{
    public $classKey = modDashboardWidget::class;
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';

    public $canCreate = false;
    public $canEdit = false;
    public $canRemove = false;

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'query' => '',
            'exclude' => 'creator'
        ]);
        $canManage = $this->modx->hasPermission('dashboards');
        $this->canCreate = $canManage;
        $this->canEdit = $canManage;
        $this->canRemove = $canManage;

        return $initialized;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where([
                'modDashboardWidget.name:LIKE' => '%' . $query . '%',
                'OR:modDashboardWidget.description:LIKE' => '%' . $query . '%',
            ]);
        }
        return $c;
    }

    /**
     * Filter the query by the valueField of MODx.combo.DashboardWidgets to get the initially value displayed right
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }
        return $c;
    }

    /**
     * {@inheritDoc}
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $permissions = [
            'create' => $this->canCreate,
            'duplicate' => $this->canCreate,
            'update' => $this->canEdit,
            'delete' => $this->canRemove
        ];
        $widgetData = $object->toArray();
        $isCoreWidget = strpos($widgetData['content'], '[[++manager_path]]') === 0;
        $widgetData['isProtected'] = $isCoreWidget
            ? true
            : false
            ;

        if ($isCoreWidget) {
            unset($permissions['delete']);
        }
        $widgetData['permissions'] = $permissions;

        return $widgetData;
    }
}

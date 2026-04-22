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
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modUserGroup;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of dashboards
 * @param string $username (optional) Will filter the grid by searching for this username
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\System\Dashboard
 */
class GetList extends GetListProcessor
{
    public $classKey = modDashboard::class;
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';

    public $canCreate = false;
    public $canEdit = false;
    public $canRemove = false;

    protected $coreDashboards;

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
        $this->coreDashboards = $this->classKey::getCoreDashboards();

        return $initialized;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where([
                'modDashboard.name:LIKE' => '%' . $query . '%',
                'OR:modDashboard.description:LIKE' => '%' . $query . '%',
            ]);
        }
        $userGroup = $this->getProperty('usergroup', false);
        if (!empty($userGroup)) {
            $c->innerJoin(modUserGroup::class, 'UserGroups');
            $c->where([
                'UserGroups.id' => $userGroup,
            ]);
        }
        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }
        return $c;
    }

    /**
     * @param xPDOObject|modDashboard $object
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
        $dashboardData = $object->toArray();
        $dashboardName = $object->get('name');
        $isCoreDashboard = $object->isCoreDashboard($dashboardName);

        $dashboardData['reserved'] = ['name' => $this->coreDashboards];
        $dashboardData['isProtected'] = $isCoreDashboard;
        $dashboardData['creator'] = $isCoreDashboard ? 'modx' : strtolower($this->modx->lexicon('user')) ;
        if ($isCoreDashboard) {
            unset($permissions['delete']);
        }
        $dashboardData['permissions'] = $permissions;

        return $dashboardData;
    }
}

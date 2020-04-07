<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Role;


use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modUserGroupRole;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of roles
 * @param boolean $addNone If true, will add a role of None
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Security\Role
 */
class GetList extends GetListProcessor
{
    public $classKey = modUserGroupRole::class;
    public $languageTopics = ['user'];
    public $permission = 'view_role';
    public $defaultSortField = 'authority';
    public $canRemove = false;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $initialized = parent::initialize();

        $this->setDefaultProperties(['addNone' => false]);

        if ($this->getProperty('sort') === 'rolename_link') {
            $this->setProperty('sort', 'name');
        }

        $this->canRemove = $this->modx->hasPermission('delete_role');

        return $initialized;
    }

    /**
     * Filter the query by the valueField of MODx.combo.Role to get the initially value displayed right
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $id = $this->getProperty('id', '');

        if (!empty($id)) {
            $c->where([$c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id]);
        }

        return $c;
    }

    /**
     * {@inheritDoc}
     * @param array $list
     * @return array
     */
    public function beforeIteration(array $list)
    {
        if ($this->getProperty('addNone', false)) {
            $list[] = ['id' => 0, 'name' => $this->modx->lexicon('none')];
        }

        return $list;
    }

    /**
     * {@inheritDoc}
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $isCoreRole = $object->get('id') === 1
            || $object->get('id') === 2
            || $object->get('name') === 'Super User'
            || $object->get('name') === 'Member';

        $perm = [];
        if (!$isCoreRole) {
            $perm[] = 'edit';
            if ($this->canRemove) {
                $perm[] = 'remove';
            }
        }
        $objectArray['perm'] = implode(' ', $perm);

        return $objectArray;
    }
}

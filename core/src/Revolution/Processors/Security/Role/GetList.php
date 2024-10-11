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
use MODX\Revolution\modUserGroupMember;
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

    public $canCreate = false;
    public $canEdit = false;
    public $canRemove = false;
    protected $coreRoles;

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

        $this->canCreate = $this->modx->hasPermission('new_role') && $this->modx->hasPermission('save_role');
        $this->canEdit = $this->modx->hasPermission('edit_role') && $this->modx->hasPermission('save_role');
        $this->canRemove = $this->modx->hasPermission('delete_role');
        $this->coreRoles = $this->classKey::getCoreRoles();

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
     * See if the Role is assigned to any users
     * @return boolean
     */
    public function isAssigned(int $id)
    {
        $c = $this->modx->newQuery(modUserGroupMember::class);
        $c = $c->where(['role' => $id]);

        return $this->modx->getCount(modUserGroupMember::class, $c) > 0;
    }

    /**
     * {@inheritDoc}
     * @param xPDOObject|modUserGroupRole $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        // Note: Role does not have a checkPolicy() method
        $permissions = [
            'create' => $this->canCreate,
            'update' => $this->canEdit,
            'delete' => $this->canRemove
        ];

        $roleData = $object->toArray();
        $roleId = $object->get('id');
        $roleName = $object->get('name');
        $isCoreRole = $object->isCoreRole($roleName);

        if ($isCoreRole) {
            $baseKey = '_role_' . strtolower(str_replace(' ', '', $roleName)) . '_';
            $roleData['name_trans'] = $this->modx->lexicon($baseKey . 'name');
            $roleData['description_trans'] = $this->modx->lexicon($baseKey . 'description');
        } else {
            if ($this->isAssigned($roleId)) {
                $roleData['isAssigned'] = 1;
            }
        }
        $roleData['reserved'] = ['name' => $this->coreRoles];
        $roleData['isProtected'] = $isCoreRole;
        $roleData['creator'] = $isCoreRole ? 'modx' : strtolower($this->modx->lexicon('user')) ;
        $roleData['permissions'] = !$isCoreRole ? $permissions : [] ;

        return $roleData;
    }
}

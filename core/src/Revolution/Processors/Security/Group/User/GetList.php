<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Group\User;

use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserGroupRole;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of users in a usergroup
 * @param boolean $combo (optional) If true, will append a (anonymous) row
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Security\Group\User
 */
class GetList extends GetListProcessor
{
    public $classKey = modUser::class;
    public $defaultSortField = 'username';
    public $permission = 'usergroup_user_list';
    public $languageTopics = ['user'];

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'usergroup' => false,
            'username' => '',
        ]);

        return parent::initialize();
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin(modUserGroupMember::class, 'UserGroupMembers');
        $c->innerJoin(modUserGroup::class, 'UserGroup', 'UserGroupMembers.user_group = UserGroup.id');
        $c->leftJoin(modUserGroupRole::class, 'UserGroupRole', 'UserGroupMembers.role = UserGroupRole.id');

        $userGroup = $this->getProperty('usergroup', 0);
        $c->where(['UserGroupMembers.user_group' => $userGroup]);

        $username = $this->getProperty('username', '');
        if (!empty($username)) {
            $c->where([
                $c->getAlias() . '.username:LIKE' => '%' . $username . '%',
            ]);
        }

        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns($this->classKey, $c->getAlias(), '', ['id', 'username']));
        $c->select([
            'usergroup' => 'UserGroup.id',
            'usergroup_name' => 'UserGroup.name',
            'role' => 'UserGroupRole.id',
            'role_name' => 'UserGroupRole.name',
            'authority' => 'UserGroupRole.authority',
        ]);
        if ($this->getProperty('sort') !== 'authority') {
            $c->sortby('authority', 'ASC');
        }

        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray('', false, true);
        $objectArray['role_name'] .= ' - ' . $objectArray['authority'];

        return $objectArray;
    }
}

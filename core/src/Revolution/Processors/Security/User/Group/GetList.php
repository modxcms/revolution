<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\User\Group;

use MODX\Revolution\modObjectGetListProcessor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserGroupRole;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of groups for a user
 * @package MODX\Revolution\Processors\Security\User\Group
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = modUserGroupMember::class;
    public $languageTopics = ['user'];
    public $permission = 'edit_user';

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties(['user' => 0]);

        return parent::initialize();
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin(modUserGroupRole::class, 'UserGroupRole');
        $c->innerJoin(modUserGroup::class, 'UserGroup');
        $c->innerJoin(modUser::class, 'User', [
            'User.id' => 'modUserGroupMember.member',
            'User.id' => $this->getProperty('user'),
        ]);
        $c->where(['modUserGroupMember.member' => $this->getProperty('user')]);

        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns(modUserGroupMember::class, 'modUserGroupMember'));
        $c->select([
            'rolename' => 'UserGroupRole.name',
            'name' => 'UserGroup.name',
        ]);
        $id = $this->getProperty('id', 0);
        if (!empty($id)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }

        return $c;
    }
}

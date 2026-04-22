<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\User;

use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserProfile;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of users
 * @param string $username (optional) Will filter the grid by searching for this username
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Security\User
 */
class GetList extends GetListProcessor
{
    public $classKey = modUser::class;
    public $languageTopics = ['user'];
    public $permission = 'view_user';
    public $defaultSortField = 'username';

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
            'usergroup' => false,
            'query' => '',
        ]);
        if ($this->getProperty('sort') === 'username_link') {
            $this->setProperty('sort', 'username');
        }
        if ($this->getProperty('sort') === 'id') {
            $this->setProperty('sort', $this->modx->getAlias($this->classKey) . '.id');
        }

        $this->canCreate = $this->modx->hasPermission('new_user') && $this->modx->hasPermission('save_user');
        $this->canEdit = $this->modx->hasPermission('edit_user') && $this->modx->hasPermission('save_user');
        $this->canRemove = $this->modx->hasPermission('delete_user');

        return $initialized;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin(modUserProfile::class, 'Profile');

        $queryChunks = explode(':', $this->getProperty('query', ''));
        if (count($queryChunks) === 2) {
            list($field, $query) = $queryChunks;
            if (in_array($field, array_keys($this->modx->getFields(modUserProfile::class)))) {
                $c->where(["Profile.$field:LIKE" => '%' . $query . '%']);
            }
        } else {
            $query = current($queryChunks);
            if (!empty($query)) {
                $c->where([
                    $c->getAlias() . '.username:LIKE' => '%' . $query . '%',
                    'Profile.fullname:LIKE' => '%' . $query . '%',
                    'Profile.email:LIKE' => '%' . $query . '%'
                ], xPDOQuery::SQL_OR);
            }
        }

        $userGroup = $this->getProperty('usergroup', 0);
        if (!empty($userGroup)) {
            if ($userGroup === 'anonymous') {
                $c->join(modUserGroupMember::class, 'UserGroupMembers', 'LEFT OUTER JOIN');
                $c->where(['UserGroupMembers.user_group' => null]);
            } else {
                $c->distinct();
                $c->innerJoin(modUserGroupMember::class, 'UserGroupMembers');
                $c->where(['UserGroupMembers.user_group' => $userGroup]);
            }
        }
        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns(modUser::class, 'modUser'));
        $c->select($this->modx->getSelectColumns(modUserProfile::class, 'Profile', '', ['fullname', 'email', 'blocked']));

        $id = $this->getProperty('id', 0);
        if (!empty($id)) {
            $c->where([$c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id]);
        }

        return $c;
    }

    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $userData = $object->toArray();
        $userData['blocked'] = $object->get('blocked') ? true : false;
        $userData['permissions'] = [
            'create' => $this->canCreate,
            'duplicate' => $this->canCreate,
            'update' => $this->canEdit,
            'delete' => $this->canRemove
        ];
        unset($userData['password'], $userData['cachepwd'], $userData['salt']);

        return $userData;
    }
}

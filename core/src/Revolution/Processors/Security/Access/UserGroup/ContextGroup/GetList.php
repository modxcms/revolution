<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\UserGroup\ContextGroup;

use MODX\Revolution\modAccessContextGroup;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modContextGroup;
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupRole;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of Context Group ACLs for a User Group.
 *
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\ContextGroup
 */
class GetList extends GetListProcessor
{
    public $classKey = modAccessContextGroup::class;
    public $languageTopics = ['access', 'context'];
    public $permission = 'access_permissions';
    public $defaultSortField = 'target';

    /** @var modUserGroup|null $userGroup */
    public $userGroup;

    public $canCreate = false;
    public $canEdit = false;
    public $canRemove = false;

    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'usergroup' => 0,
            'contextGroup' => false,
            'policy' => false,
        ]);
        $userGroup = $this->getProperty('usergroup', false);
        if (!empty($userGroup)) {
            $this->userGroup = $this->modx->getObject(modUserGroup::class, $userGroup);
        }
        if ($this->getProperty('sort') == 'role_display') {
            $this->setProperty('sort', 'authority');
        }
        $canChange = $this->modx->hasPermission('usergroup_edit') && $this->modx->hasPermission('usergroup_save');
        $this->canCreate = $canChange;
        $this->canEdit = $canChange;
        $this->canRemove = $canChange;

        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $userGroup = $this->getProperty('usergroup');
        $c->where([
            'principal_class' => modUserGroup::class,
            'principal' => $userGroup,
        ]);
        $contextGroup = $this->getProperty('contextGroup', false);
        if (!empty($contextGroup)) {
            $c->where(['target' => $contextGroup]);
        }
        $policy = $this->getProperty('policy', false);
        if (!empty($policy)) {
            $c->where(['policy' => $policy]);
        }

        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->leftJoin(modUserGroupRole::class, 'Role', ['Role.authority = modAccessContextGroup.authority']);
        $c->leftJoin(modAccessPolicy::class, 'Policy');
        $c->leftJoin(modContextGroup::class, 'Target');
        $c->select($this->modx->getSelectColumns(modAccessContextGroup::class, 'modAccessContextGroup'));
        $c->select([
            'name' => '`Target`.`name`',
            'policy_name' => '`Policy`.`name`',
            'policy_data' => '`Policy`.`data`',
            'role_display' => 'CONCAT_WS(\' - \',`modAccessContextGroup`.`authority`,`Role`.`name`)',
        ]);
        if ($this->getProperty('isGroupingGrid')) {
            $groupBy = $this->getProperty('groupBy');
            if (!empty($groupBy)) {
                $groupKeys = [
                    'role_display' => '`modAccessContextGroup`.`authority`',
                    'policy_name' => '`Policy`.`name`',
                    'name' => '`Target`.`name`',
                ];
                if (!isset($groupKeys[$groupBy])) {
                    return $c;
                }
                $this->setGroupSort($c, $this->getProperty('sort'), $groupBy, $groupKeys[$groupBy]);
            }
        }

        return $c;
    }

    public function useSecondaryGroupCondition(string $sortBy, string $groupBy, string $groupKey): bool
    {
        return $sortBy === 'authority' && $groupBy === 'role_display';
    }

    /**
     * @param xPDOObject|modAccessContextGroup $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $permissions = [
            'create' => $this->canCreate,
            'update' => $this->canEdit,
            'delete' => $this->canRemove,
        ];

        $aclData = $object->toArray();
        if (empty($aclData['name'])) {
            $aclData['name'] = '(' . $this->modx->lexicon('none') . ')';
        }

        $aclData['policyPermissions'] = [];
        $data = $aclData['policy_data'] ?? null;
        unset($aclData['policy_data']);
        $data = $this->modx->fromJSON($data);
        if (!empty($data)) {
            $aclData['policyPermissions'] = array_keys($data, 1);
        }
        $aclData['permissions'] = $permissions;

        return $aclData;
    }
}

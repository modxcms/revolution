<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\UserGroup\AccessNamespace;

use MODX\Revolution\modAccessNamespace;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupRole;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of ACLs.
 * @param string $type The type of ACL object
 * @param string $target (optional) The target of the ACL. Defaults to 0.
 * @param string $principal_class The class_key for the principal. Defaults to modUserGroup.
 * @param string $principal (optional) The principal ID. Defaults to 0.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\AccessNamespace
 */
class GetList extends GetListProcessor
{
    public $classKey = modAccessNamespace::class;
    public $languageTopics = ['access'];
    public $permission = 'access_permissions';
    public $defaultSortField = 'target';

    /** @var modUserGroup $userGroup */
    public $userGroup;

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'usergroup' => 0,
            'namespace' => false,
            'policy' => false,
        ]);
        $userGroup = $this->getProperty('usergroup', false);
        if (!empty($userGroup)) {
            $this->userGroup = $this->modx->getObject(modUserGroup::class, $userGroup);
        }
        return $initialized;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $userGroup = $this->getProperty('usergroup');
        $c->where([
            'principal_class' => modUserGroup::class,
            'principal' => $userGroup,
        ]);
        $namespace = $this->getProperty('namespace', false);
        if (!empty($namespace)) {
            $c->where(['target' => $namespace]);
        }
        $policy = $this->getProperty('policy', false);
        if (!empty($policy)) {
            $c->where(['policy' => $policy]);
        }

        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->leftJoin(modNamespace::class, 'Target');
        $c->leftJoin(modUserGroupRole::class, 'Role', ['Role.authority = modAccessNamespace.authority']);
        $c->leftJoin(modAccessPolicy::class, 'Policy');
        $c->select($this->modx->getSelectColumns(modAccessNamespace::class, 'modAccessNamespace'));
        $c->select([
            'name' => 'Target.name',
            'role_name' => 'Role.name',
            'policy_name' => 'Policy.name',
            'policy_data' => 'Policy.data',
        ]);

        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array
     * @throws \xPDO\xPDOException
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        if (empty($objectArray['name'])) {
            $objectArray['name'] = '(' . $this->modx->lexicon('none') . ')';
        }
        $objectArray['authority_name'] = !empty($objectArray['role_name']) ? $objectArray['role_name'] . ' - ' . $objectArray['authority'] : $objectArray['authority'];

        /* get permissions list */
        $data = $objectArray['policy_data'];
        unset($objectArray['policy_data']);
        $data = $this->modx->fromJSON($data);
        if (!empty($data)) {
            $permissions = [];
            foreach ($data as $permission => $enabled) {
                if (!$enabled) {
                    continue;
                }
                $permissions[] = $permission;
            }
            $objectArray['permissions'] = implode(', ', $permissions);
        }


        $cls = 'pedit premove';

        $objectArray['cls'] = $cls;
        $objectArray['menu'] = [
            [
                'text' => $this->modx->lexicon('access_namespace_update'),
                'handler' => 'this.updateAcl',
            ],
            '-',
            [
                'text' => $this->modx->lexicon('access_namespace_remove'),
                'handler' => 'this.confirm.createDelegate(this,["Security/Access/UserGroup/AccessNamespace/Remove"])',
            ],
        ];

        return $objectArray;
    }
}

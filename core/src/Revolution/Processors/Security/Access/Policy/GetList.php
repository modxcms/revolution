<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\Policy;

use MODX\Revolution\modAccessCategory;
use MODX\Revolution\modAccessContext;
use MODX\Revolution\modAccessNamespace;
use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\modAccessPolicyTemplateGroup;
use MODX\Revolution\modAccessResourceGroup;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\Sources\modAccessMediaSource;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of policies.
 * @param boolean $combo (optional) If true, will append a 'no policy' row to the beginning.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Security\Access\Policy
 */
class GetList extends GetListProcessor
{
    public $classKey = modAccessPolicy::class;
    public $checkListPermission = false;
    public $objectType = 'policy';
    public $permission = 'policy_view';
    public $languageTopics = ['policy', 'en:policy'];

    /** @param boolean $isGridFilter Indicates the target of this list data is a filter field */
    protected $isGridFilter = false;

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'sortAlias' => 'modAccessPolicy',
            'group' => false,
            'combo' => false,
            'query' => '',
        ]);
        $this->isGridFilter = $this->getProperty('isGridFilter', false);
        return $initialized;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin(modAccessPolicyTemplate::class, 'Template');

        $group = $this->getProperty('group');
        if (!empty($group)) {
            $group = is_array($group) ? $group : explode(',', $group);
            $c->innerJoin(
                modAccessPolicyTemplateGroup::class,
                'TemplateGroup',
                'TemplateGroup.id = Template.template_group'
            );
            $c->where(['TemplateGroup.name:IN' => $group]);
        }

        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where([
                'modAccessPolicy.name:LIKE' => '%' . $query . '%',
                'OR:modAccessPolicy.description:LIKE' => '%' . $query . '%',
                'OR:Template.name:LIKE' => '%' . $query . '%'
            ]);
        }

        /*
            When this class is used to fetch data for a grid filter's store (combo),
            limit results to only those policies present in the current grid.
        */
        if ($this->isGridFilter) {
            $userGroup = $this->getProperty('usergroup', '');
            $targetGrid = $this->getProperty('targetGrid', '');

            if (!empty($userGroup) && !empty($targetGrid)) {
                switch ($targetGrid) {
                    case 'MODx.grid.UserGroupContext':
                        $joinClass = modAccessContext::class;
                        $joinAlias = 'modAccessContext';
                        // Note that context pk is a string ('key' is the dataIndex), not an int id
                        $context = $this->getProperty('context', '');
                        if (!empty($context)) {
                            $c->where([
                                '`modAccessContext`.`target`' => $context
                            ]);
                        }
                        break;
                    case 'MODx.grid.UserGroupResourceGroup':
                        $joinClass = modAccessResourceGroup::class;
                        $joinAlias = 'modAccessResourceGroup';
                        $resourceGroup = $this->getProperty('resourceGroup', '');
                        if (!empty($resourceGroup)) {
                            $c->where([
                                '`modAccessResourceGroup`.`target`' => (int)$resourceGroup
                            ]);
                        }
                        break;
                    case 'MODx.grid.UserGroupCategory':
                        $joinClass = modAccessCategory::class;
                        $joinAlias = 'modAccessCategory';
                        $category = $this->getProperty('category', '');
                        if (!empty($category)) {
                            $c->where([
                                '`modAccessCategory`.`target`' => (int)$category
                            ]);
                        }
                        break;
                    case 'MODx.grid.UserGroupSource':
                        $joinClass = modAccessMediaSource::class;
                        $joinAlias = 'modAccessMediaSource';
                        $source = $this->getProperty('source', '');
                        if (!empty($source)) {
                            $c->where([
                                '`modAccessMediaSource`.`target`' => (int)$source
                            ]);
                        }
                        break;
                    case 'MODx.grid.UserGroupNamespace':
                        $joinClass = modAccessNamespace::class;
                        $joinAlias = 'modAccessNamespace';
                        // Note that namespace pk is a string ('name' is the dataIndex), not an int id
                        $namespace = $this->getProperty('namespace', '');
                        if (!empty($namespace)) {
                            $c->where([
                                '`modAccessNamespace`.`target`' => $namespace
                            ]);
                        }
                        break;
                }
                $c->innerJoin(
                    $joinClass,
                    $joinAlias,
                    [
                        '`' . $joinAlias . '`.`policy` = `modAccessPolicy`.`id`',
                        '`' . $joinAlias . '`.`principal` = ' . $userGroup,
                        '`' . $joinAlias . '`.`principal_class` = ' . $this->modx->quote(modUserGroup::class)
                    ]
                );
                $c->groupby('`modAccessPolicy`.`id`');
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
        $subc = $this->modx->newQuery(modAccessPermission::class);
        $subc->select('COUNT(modAccessPermission.id)');
        $subc->where(['modAccessPermission.template = Template.id']);
        $subc->prepare();
        $c->select($this->modx->getSelectColumns(modAccessPolicy::class, 'modAccessPolicy'));
        $c->select(['template_name' => 'Template.name']);
        $c->select('(' . $subc->toSql() . ') AS ' . $this->modx->escape('total_permissions'));
        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }

        return $c;
    }

    /**
     * @param array $list
     * @return array
     */
    public function beforeIteration(array $list)
    {
        if ($this->getProperty('combo', false)) {
            $list[] = [
                'id' => '',
                'name' => $this->modx->lexicon('no_policy_option'),
            ];
        }
        return $list;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $policy = $object->toArray();

        $policy['cls'] = $this->prepareRowClasses($object);

        $permissions = [];
        if (!empty($policy['total_permissions'])) {
            $data = $object->get('data');
            $ct = 0;
            if (!empty($data)) {
                foreach ($data as $k => $v) {
                    if (!empty($v)) {
                        $permissions[] = $k;
                        $ct++;
                    }
                }
            }
            $policy['active_permissions'] = $ct;
            $policy['active_of'] = $this->modx->lexicon('active_of', [
                'active' => $policy['active_permissions'],
                'total' => $policy['total_permissions'],
            ]);
            $policy['permissions'] = $permissions;
        }

        unset($policy['data']);

        $policy['description_trans'] = $this->modx->lexicon($policy['description']);

        return $policy;
    }

    /**
     * @param xPDOObject|modAccessPolicy $object
     *
     * @return string
     */
    protected function prepareRowClasses(xPDOObject $object)
    {
        if (!$object->isCorePolicy($object->get('name'))) {
            return implode(' ', [
                static::CLASS_ALLOW_EDIT,
                static::CLASS_ALLOW_REMOVE
            ]);
        }

        return static::CLASS_ALLOW_EDIT;
    }
}

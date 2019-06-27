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

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\modAccessPolicyTemplateGroup;
use MODX\Revolution\modObjectGetListProcessor;
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
class GetList extends modObjectGetListProcessor
{
    public $classKey = modAccessPolicy::class;
    public $checkListPermission = false;
    public $objectType = 'policy';
    public $permission = 'policy_view';
    public $languageTopics = ['policy'];

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'sortAlias' => modAccessPolicy::class,
            'group' => false,
            'combo' => false,
            'query' => '',
        ]);
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
            $c->innerJoin(modAccessPolicyTemplateGroup::class, 'TemplateGroup',
                'TemplateGroup.id = Template.template_group');
            $c->where(['TemplateGroup.name:IN' => $group]);
        }
        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where([
                'modAccessPolicy.name:LIKE' => '%' . $query . '%',
                'OR:modAccessPolicy.description:LIKE' => '%' . $query . '%'
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
        $subc = $this->modx->newQuery(modAccessPermission::class);
        $subc->select('COUNT(modAccessPermission.id)');
        $subc->where(['modAccessPermission.template = Template.id',]);
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
        $core = ['Resource', 'Object', 'Administrator', 'Load Only', 'Load, List and View'];
        $policyArray = $object->toArray();
        $permissions = [];
        $cls = 'pedit';
        if (!in_array($object->get('name'), $core)) {
            $cls .= ' premove';
        }
        $policyArray['cls'] = $cls;
        if (!empty($policyArray['total_permissions'])) {
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
            $policyArray['active_permissions'] = $ct;
            $policyArray['active_of'] = $this->modx->lexicon('active_of', [
                'active' => $policyArray['active_permissions'],
                'total' => $policyArray['total_permissions'],
            ]);
            $policyArray['permissions'] = $permissions;
        }

        unset($policyArray['data']);

        return $policyArray;
    }
}

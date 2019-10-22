<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\Policy\Template;

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\modAccessPolicyTemplateGroup;
use MODX\Revolution\modObjectGetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of policy templates.
 * @param boolean $combo (optional) If true, will append a 'no policy' row to the beginning.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default
 * @package MODX\Revolution\Processors\Security\Access\Policy\Template
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = modAccessPolicyTemplate::class;
    public $checkListPermission = false;
    public $objectType = 'policy_template';
    public $permission = 'policy_template_view';
    public $languageTopics = ['policy'];

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'sortAlias' => 'modAccessPolicyTemplate',
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
        $c->innerJoin(modAccessPolicyTemplateGroup::class, 'TemplateGroup');
        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where([
                'modAccessPolicyTemplate.name:LIKE' => '%' . $query . '%',
                'OR:modAccessPolicyTemplate.description:LIKE' => '%' . $query . '%',
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
        $subQuery = $this->modx->newQuery(modAccessPermission::class);
        $subQuery->select('COUNT(modAccessPermission.id)');
        $subQuery->where([
            'modAccessPermission.template = modAccessPolicyTemplate.id',
        ]);
        $subQuery->prepare();
        $c->select($this->modx->getSelectColumns(modAccessPolicyTemplate::class, 'modAccessPolicyTemplate'));
        $c->select(['template_group_name' => 'TemplateGroup.name']);
        $c->select('(' . $subQuery->toSql() . ') AS ' . $this->modx->escape('total_permissions'));
        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }

        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $core = ['ResourceTemplate', 'ObjectTemplate', 'AdministratorTemplate', 'ElementTemplate'];

        $objectArray = $object->toArray();
        $cls = ['pedit'];
        if (!in_array($object->get('name'), $core)) {
            $cls[] = 'premove';
        }
        $objectArray['cls'] = implode(' ', $cls);

        return $objectArray;
    }
}

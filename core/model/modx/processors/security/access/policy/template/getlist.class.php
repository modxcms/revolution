<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Gets a list of policy templates.
 *
 * @param boolean $combo (optional) If true, will append a 'no policy' row to
 * the beginning.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class modAccessPolicyTemplateGetListProcessor extends modObjectGetListProcessor {
    public $checkListPermission = false;
    public $objectType = 'policy_template';
    public $classKey = 'modAccessPolicyTemplate';
    public $permission = 'policy_template_view';
    public $languageTopics = array('policy');

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'sortAlias' => 'modAccessPolicyTemplate',
            'query' => '',
        ));
        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->innerJoin('modAccessPolicyTemplateGroup','TemplateGroup');
        $query = $this->getProperty('query','');
        if (!empty($query)) {
            $c->where(array(
                'modAccessPolicyTemplate.name:LIKE' => '%'.$query.'%',
                'OR:modAccessPolicyTemplate.description:LIKE' => '%'.$query.'%',
            ));
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $subQuery = $this->modx->newQuery('modAccessPermission');
        $subQuery->select('COUNT(modAccessPermission.id)');
        $subQuery->where(array(
            'modAccessPermission.template = modAccessPolicyTemplate.id',
        ));
        $subQuery->prepare();
        $c->select($this->modx->getSelectColumns('modAccessPolicyTemplate','modAccessPolicyTemplate'));
        $c->select(array(
            'template_group_name' => 'TemplateGroup.name',
        ));
        $c->select('('.$subQuery->toSql().') AS '.$this->modx->escape('total_permissions'));
        $id = $this->getProperty('id','');
        if (!empty($id)) {
            $c->where(array(
                $this->classKey . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ));
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $core = array('ResourceTemplate','ObjectTemplate','AdministratorTemplate','ElementTemplate');

        $objectArray = $object->toArray();
        $cls = array('pedit');
        if (!in_array($object->get('name'),$core)) {
            $cls[] = 'premove';
        }
        $objectArray['cls'] = implode(' ',$cls);
        return $objectArray;
    }
}
return 'modAccessPolicyTemplateGetListProcessor';

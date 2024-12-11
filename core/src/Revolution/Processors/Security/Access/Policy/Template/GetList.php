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
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\modAccessPolicyTemplateGroup;
use MODX\Revolution\Processors\Model\GetListProcessor;
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
class GetList extends GetListProcessor
{
    public $classKey = modAccessPolicyTemplate::class;
    public $checkListPermission = false;
    public $objectType = 'policy_template';
    public $permission = 'policy_template_view';
    public $languageTopics = ['policy', 'en:policy'];

     /** @param boolean $isGridFilter Indicates the target of this list data is a filter field */
     protected $isGridFilter = false;
     public $canCreate = false;
     public $canEdit = false;
     public $canRemove = false;
     protected $corePolicyTemplates;
     protected $corePolicyTemplateGroups;

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'sortAlias' => 'modAccessPolicyTemplate',
            'query' => '',
            'exclude' => 'creator'
        ]);
        $this->isGridFilter = $this->getProperty('isGridFilter', false);

        $this->canCreate = $this->modx->hasPermission('policy_template_new') && $this->modx->hasPermission('policy_template_save');
        $this->canEdit = $this->modx->hasPermission('policy_template_edit');
        $this->canRemove = $this->modx->hasPermission('policy_template_delete');
        $this->corePolicyTemplates = $this->classKey::getCoreTemplates();
        $this->corePolicyTemplateGroups = modAccessPolicyTemplateGroup::getCoreGroups();

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
                'OR:TemplateGroup.name:LIKE' => '%' . $query . '%'
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
        $c->select($this->modx->getSelectColumns(modAccessPolicyTemplate::class, 'modAccessPolicyTemplate'));
        $c->select(['template_group_name' => 'TemplateGroup.name']);

        $subQuery = $this->modx->newQuery(modAccessPermission::class);
        $subQuery->select('COUNT(modAccessPermission.id)');
        $subQuery->where([
            'modAccessPermission.template = modAccessPolicyTemplate.id',
        ]);
        $subQuery->prepare();
        $c->select('(' . $subQuery->toSql() . ') AS ' . $this->modx->escape('total_permissions'));

        $policyCountSubQuery = $this->modx->newQuery(modAccessPolicy::class);
        $policyCountSubQuery->select('COUNT(modAccessPolicy.id)');
        $policyCountSubQuery->where([
            'modAccessPolicy.template = modAccessPolicyTemplate.id',
        ]);
        $policyCountSubQuery->prepare();
        $c->select('(' . $policyCountSubQuery->toSql() . ') AS ' . $this->modx->escape('policy_count'));

        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }

        return $c;
    }

    /**
     * @param xPDOObject|modAccessPolicyTemplate $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $permissions = [
            'create' => $this->canCreate,
            'duplicate' => $this->canCreate,
            'update' => $this->canEdit,
            'delete' => $this->canRemove
        ];
        $templateData = $object->toArray();
        $templateName = $object->get('name');
        $isCoreTemplate = $object->isCoreTemplate($templateName);

        $templateData['reserved'] = ['name' => $this->corePolicyTemplates];
        $templateData['isProtected'] = $isCoreTemplate;
        $templateData['creator'] = $isCoreTemplate ? 'modx' : strtolower($this->modx->lexicon('user')) ;
        if ($isCoreTemplate) {
            unset($permissions['delete']);
        }
        $templateData['permissions'] = $permissions;
        $templateData['description_trans'] = $this->modx->lexicon($templateData['description']);

        return $templateData;
    }

    /**
     * @param xPDOObject|modAccessPolicyTemplate $object
     * @deprecated as of MODX 3.1.0; new permissions handling replaces css class-based specifiers
     * @return string
     */
    protected function prepareRowClasses(xPDOObject $object)
    {
        if (!$object->isCoreTemplate($object->get('name'))) {
            return implode(' ', [
                static::CLASS_ALLOW_EDIT,
                static::CLASS_ALLOW_REMOVE
            ]);
        }

        return static::CLASS_ALLOW_EDIT;
    }
}

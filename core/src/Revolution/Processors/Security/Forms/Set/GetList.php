<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Forms\Set;

use MODX\Revolution\modFormCustomizationSet;
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modTemplate;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of Form Customization sets.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default action.
 * @package MODX\Revolution\Processors\Security\Forms\Set
 */
class GetList extends GetListProcessor
{
    public $classKey = modFormCustomizationSet::class;
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $defaultSortField = 'action';
    public $canEdit = false;
    public $canRemove = false;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties(['profile' => 0, 'search' => '']);
        $this->canEdit = $this->modx->hasPermission('save');
        $this->canRemove = $this->modx->hasPermission('remove');
        return parent::initialize();
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin(modTemplate::class, 'Template');
        $profile = $this->getProperty('profile');
        if (!empty($profile)) {
            $c->where(['profile' => $profile]);
        }
        $search = $this->getProperty('search');
        if (!empty($search)) {
            $c->where([
                'modFormCustomizationSet.description:LIKE' => '%' . $search . '%',
                'OR:Template.templatename:LIKE' => '%' . $search . '%',
                'OR:modFormCustomizationSet.constraint_field:LIKE' => '%' . $search . '%',
            ], null, 2);
        }
        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns(modFormCustomizationSet::class, 'modFormCustomizationSet'));
        $c->select(['Template.templatename']);
        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();

        $constraint_field = $object->get('constraint_field');
        $constraint = $object->get('constraint');
        if (!empty($constraint_field)) {
            if ($constraint === '') {
                $constraint = "'{$constraint}'";
            }
            $objectArray['constraint_data'] = $object->get('constraint_class') . '.' . $constraint_field . ' = ' . $constraint;
        }
        $objectArray['perm'] = [];
        if ($this->canEdit) {
            $objectArray['perm'][] = 'pedit';
        }
        if ($this->canRemove) {
            $objectArray['perm'][] = 'premove';
        }

        return $objectArray;
    }
}

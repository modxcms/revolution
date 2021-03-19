<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\Policy\Template\Group;

use MODX\Revolution\modAccessPolicyTemplateGroup;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;

/**
 * Gets a list of policy template groups.
 *
 * @param bool   $combo (optional) If true, will append a 'no policy' row to the beginning.
 * @param int    $start (optional) The record to start at. Defaults to 0.
 * @param int    $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort  (optional) The column to sort by.
 * @param string $dir   (optional) The direction of the sort. Default
 *
 * @package MODX\Revolution\Processors\Security\Access\Policy\Template\Group
 */
class GetList extends GetListProcessor
{
    public $classKey = modAccessPolicyTemplateGroup::class;
    public $checkListPermission = false;
    public $objectType = 'permission';
    public $permission = 'policy_template_view';
    public $languageTopics = ['policy', 'en:policy'];

    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $group = $object->toArray();

        $group['cls'] = static::CLASS_ALLOW_EDIT;
        $group['description'] = $this->modx->lexicon($group['description']);

        return $group;
    }
}

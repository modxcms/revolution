<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\ResourceGroup;

use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modResourceGroup;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of resource groups
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Security\ResourceGroup
 */
class GetList extends GetListProcessor
{
    public $classKey = modResourceGroup::class;
    public $languageTopics = ['access'];
    public $permission = 'resourcegroup_view';

    /**
     * Filter the query by the valueField of MODx.combo.ResourceGroup to get the initially value displayed right
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $key = $this->getProperty('id', '');

        if (!empty($key)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($key) ? explode(',', $key) : $key,
            ]);
        }

        return $c;
    }
}

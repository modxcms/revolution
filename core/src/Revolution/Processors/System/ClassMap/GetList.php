<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\ClassMap;

use MODX\Revolution\modClassMap;
use MODX\Revolution\modObjectGetListProcessor;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of classes in the class map
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to class.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\System\ClassMap
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = modClassMap::class;
    public $permission = 'class_map';

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $parentClass = $this->getProperty('parentClass', '');
        if (!empty($parentClass)) {
            $c->where([
                'parent_class' => $parentClass,
            ]);
        }
        return $c;
    }

    /**
     * Filter the query by the valueField of MODx.combo.ClassMap to get the initially value displayed right
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $class = $this->getProperty('class', '');
        if (!empty($class)) {
            $c->where([
                $this->classKey . '.class:IN' => is_string($class) ? explode(',', $class) : $class,
            ]);
        }
        return $c;
    }
}

<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\PackageNamespace;

use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of namespaces
 * @param string $name (optional) If set, will search by name
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\Workspace\PackageNamespace
 */
class GetList extends GetListProcessor
{
    public $classKey = modNamespace::class;
    public $languageTopics = ['namespace', 'workspace'];
    public $permission = 'namespaces';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties(['search' => false]);
        return $initialized;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $search = $this->getProperty('search', '');
        if (!empty($search)) {
            $c->where([
                'name:LIKE' => '%' . $search . '%',
                'OR:path:LIKE' => '%' . $search . '%',
            ]);
        }
        return $c;
    }

    /**
     * Filter the query by the name property to get the right value in preselectFirstValue of MODx.combo.Namespace
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $name = $this->getProperty('name', '');
        if (!empty($name)) {
            $c->where([$c->getAlias() . '.name:IN' => is_string($name) ? explode(',', $name) : $name]);
        }
        return $c;
    }

    /**
     * Prepare the Namespace for listing
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray['perm'] = [];
        $objectArray['perm'][] = 'pedit';
        $objectArray['perm'][] = 'premove';

        return $objectArray;
    }
}

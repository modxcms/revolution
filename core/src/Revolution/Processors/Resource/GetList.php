<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource;

use MODX\Revolution\modObjectGetListProcessor;
use MODX\Revolution\modResource;
use xPDO\Om\xPDOObject;

/**
 * Gets a list of resources.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @return array An array of modResources
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = modResource::class;
    public $languageTopics = ['resource'];
    public $defaultSortField = 'pagetitle';
    public $permission = 'view';

    public function prepareRow(xPDOObject $object)
    {
        $charset = $this->modx->getOption('modx_charset', null, 'UTF-8');
        $objectArray = $object->toArray();
        $objectArray['pagetitle'] = htmlentities($objectArray['pagetitle'], ENT_COMPAT, $charset);
        return $objectArray;
    }
}

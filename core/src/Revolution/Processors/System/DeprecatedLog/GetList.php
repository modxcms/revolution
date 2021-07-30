<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\DeprecatedLog;

use MODX\Revolution\modDeprecatedCall;
use MODX\Revolution\modDeprecatedMethod;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Get a list of system settings
 * @param string $key (optional) If set, will search by this value
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package modx
 * @subpackage processors.system.settings
 */
class GetList extends GetListProcessor
{
    public $classKey = modDeprecatedCall::class;
    public $languageTopics = ['system_events'];
    public $defaultSortField = 'method';
    public $defaultSortDirection = 'id';

    public function checkPermissions()
    {
        return $this->modx->hasPermission('error_log_view');
    }

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns($this->classKey, $this->modx->getAlias($this->classKey)));
        $c->leftJoin(modDeprecatedMethod::class, 'Method');
        $c->select($this->modx->getSelectColumns(modDeprecatedMethod::class, 'Method', 'method_', [
            'id', 'definition', 'since', 'recommendation'
        ]));
        return parent::prepareQueryBeforeCount($c);
    }

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();

        return $initialized;
    }

    /**
     * Prepare a setting for output
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();
        $array['caller_file'] = str_replace([
            MODX_CORE_PATH,
            MODX_BASE_PATH,
        ], [
            '{core_path}',
            '{base_path}'
        ], $array['caller_file']);
        return $array;
    }
}

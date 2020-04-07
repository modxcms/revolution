<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\DatabaseTable;

use MODX\Revolution\Processors\DriverSpecificProcessor;

/**
 * @package MODX\Revolution\Processors\System\DatabaseTable
 */
abstract class OptimizeDatabaseAbstract extends DriverSpecificProcessor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('database');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['system_info'];
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $optimized = $this->optimize();
        if ($optimized !== true) {
            $output = $this->failure($this->modx->lexicon('optimize_database_err') . $optimized);
        } else {
            $this->logManagerAction();
            $output = $this->success();
        }
        return $output;
    }

    /**
     * Optimize the database
     * @return boolean
     */
    abstract public function optimize();

    /**
     * Log a manager action showing the optimized table
     * @return void
     */
    public function logManagerAction()
    {
        if ($this->modx->error->status) {
            $this->modx->logManagerAction('database_optimize', 'database', 0);
        }
    }
}

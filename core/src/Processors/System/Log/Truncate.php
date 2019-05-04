<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Log;

use MODX\Revolution\modManagerLog;
use MODX\Revolution\modProcessor;

/**
 * Clears the manager log actions
 * @package MODX\Revolution\Processors\System\Log
 */
class Truncate extends modProcessor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('logs');
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $this->modx->exec("TRUNCATE {$this->modx->getTableName(modManagerLog::class)}");
        
        return $this->success();
    }
}

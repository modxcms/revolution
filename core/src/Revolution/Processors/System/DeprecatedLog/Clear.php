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
use MODX\Revolution\Processors\Processor;

/**
 * Clear the error log
 * @package MODX\Revolution\Processors\System\ErrorLog
 */
class Clear extends Processor
{
    /**
     * @return mixed
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('error_log_erase');
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $this->modx->removeCollection(modDeprecatedMethod::class, []);
        $this->modx->removeCollection(modDeprecatedCall::class, []);
        return $this->success();
    }
}

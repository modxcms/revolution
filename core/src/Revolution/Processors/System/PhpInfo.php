<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System;

use MODX\Revolution\Processors\Processor;

/**
 * Display phpinfo()
 * @package MODX\Revolution\Processors\System
 */
class PhpInfo extends Processor
{
    /**
     * @return mixed
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_sysinfo');
    }

    /**
     * @return mixed|void
     */
    public function process()
    {
        echo '<div style="font-size: 1.3em;">';
        phpinfo();
        echo '</div>';
        @session_write_close();
        die();
    }
}

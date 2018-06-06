<?php

namespace MODX\Processors\System;

use MODX\Processors\modProcessor;

/**
 * Display phpinfo()
 *
 * @package modx
 * @subpackage processors.system
 */
class PhpInfo extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_sysinfo');
    }


    public function process()
    {
        echo '<div style="font-size: 1.3em;">';
        phpinfo();
        echo '</div>';
        @session_write_close();
        die();
    }
}

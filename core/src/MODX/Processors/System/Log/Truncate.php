<?php

namespace MODX\Processors\System\Log;

use MODX\Processors\modProcessor;

/**
 * Clears the manager log actions
 *
 * @package modx
 * @subpackage processors.system.log
 */
class Truncate extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('logs');
    }


    public function process()
    {
        $this->modx->exec("TRUNCATE {$this->modx->getTableName('modManagerLog')}");

        return $this->success();
    }
}

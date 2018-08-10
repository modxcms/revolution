<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Clears the manager log actions
 *
 * @package modx
 * @subpackage processors.system.log
 */
class modSystemLogTruncateProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('logs');
    }
    public function process() {
        $this->modx->exec("TRUNCATE {$this->modx->getTableName('modManagerLog')}");
        return $this->success();
    }
}
return 'modSystemLogTruncateProcessor';

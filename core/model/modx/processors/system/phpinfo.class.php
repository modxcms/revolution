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
 * Display phpinfo()
 *
 * @package modx
 * @subpackage processors.system
 */
class modSystemPhpInfoProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_sysinfo');
    }

    public function process() {
        echo '<div style="font-size: 1.3em;">';
        phpinfo();
        echo '</div>';
        @session_write_close();
        die();
    }
}
return 'modSystemPhpInfoProcessor';

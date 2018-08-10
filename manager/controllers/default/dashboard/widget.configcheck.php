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
 * Renders the config check box
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetConfigCheck extends modDashboardWidgetInterface {
    public $cssBlockClass = 'dashboard-block-variable';

    public function render() {
        $o = '';
        /* do some config checks */
        $modx =& $this->modx;
        $config_check_results = '';
        $success = include $this->modx->getOption('processors_path') . 'system/config_check.inc.php';
        if (!$success) {
            $o = $config_check_results;
        }
        return $o;
    }
}
return 'modDashboardWidgetConfigCheck';

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
 *
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetConfigCheck extends modDashboardWidgetInterface
{
    public $cssBlockClass = 'dashboard-block-variable';

    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        /** @var modProcessorResponse $response */
        $response = $this->modx->runProcessor('system/configcheck');

        $this->modx->getService('smarty', 'smarty.modSmarty');
        $this->modx->smarty->assign('warnings', $response->getObject());

        return $this->modx->smarty->fetch('dashboard/configcheck.tpl');
    }
}

return 'modDashboardWidgetConfigCheck';

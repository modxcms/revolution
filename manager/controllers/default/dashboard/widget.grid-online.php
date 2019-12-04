<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modDashboardWidgetInterface;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\Processors\Security\User\GetOnline;
use MODX\Revolution\Smarty\modSmarty;

/**
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetWhoIsOnline extends modDashboardWidgetInterface
{
    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        /** @var ProcessorResponse $res */
        $res = $this->modx->runProcessor(GetOnline::class, [
            'limit' => 10,
        ]);
        $data = [];
        if (!$res->isError()) {
            $data = $res->getResponse();
            if (is_string($data)) {
                $data = json_decode($data, true);
            }
        }
        $this->modx->getService('smarty', modSmarty::class);
        $this->modx->smarty->assign('data', $data);
        $this->modx->smarty->assign('can_view_logs', $this->modx->hasPermission('logs'));

        return $this->modx->smarty->fetch('dashboard/onlineusers.tpl');
    }
}

return 'modDashboardWidgetWhoIsOnline';

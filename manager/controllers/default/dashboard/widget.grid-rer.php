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
use MODX\Revolution\Processors\Security\User\GetRecentlyEditedResources;
use MODX\Revolution\Smarty\modSmarty;

/**
 * Renders a grid of recently edited resources by the active user
 *
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetRecentlyEditedResources extends modDashboardWidgetInterface
{
    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        /** @var ProcessorResponse $res */
        $res = $this->modx->runProcessor(GetRecentlyEditedResources::class, [
            'limit' => 10,
            'user' => $this->modx->user->get('id'),
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

        return $this->modx->smarty->fetch('dashboard/recentlyeditedresources.tpl');
    }
}

return 'modDashboardWidgetRecentlyEditedResources';

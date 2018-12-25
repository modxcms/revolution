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
        /** @var modProcessorResponse $res */
        $res = $this->modx->runProcessor('security/user/getonline', [
            'limit' => 10,
        ]);
        $data = [];
        if (!$res->isError()) {
            $data = $res->getResponse();
            if (is_string($data)) {
                $data = json_decode($data, true);
            }
        }
        $this->modx->getService('smarty', 'smarty.modSmarty');
        $this->modx->smarty->assign('data', $data);

        return $this->modx->smarty->fetch('dashboard/onlineusers.tpl');
    }
}

return 'modDashboardWidgetWhoIsOnline';

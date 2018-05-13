<?php

use MODX\modDashboardWidgetInterface;
use MODX\Processors\modProcessorResponse;

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
        $response = $this->modx->runProcessor('System/ConfigCheck');

        $this->modx->getService('smarty', 'smarty.modSmarty');
        $this->modx->smarty->assign('warnings', $response->getObject());

        return $this->modx->smarty->fetch('dashboard/configcheck.tpl');
    }
}

return 'modDashboardWidgetConfigCheck';
<?php

use MODX\Revolution\modChunk;
use MODX\Revolution\modDashboardWidgetInterface;
use MODX\Revolution\Smarty\modSmarty;

/**
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetButtons extends modDashboardWidgetInterface
{
    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        $this->modx->getService('smarty', modSmarty::class);
        foreach ($this->widget->toArray() as $key => $value) {
            $this->modx->smarty->assign($key, $value);
        }

        return $this->modx->smarty->fetch('dashboard/buttons.tpl');
    }


    /**
     * @return string
     * @throws Exception
     */
    public function process()
    {
        /** @var modChunk $chunk */
        $chunk = $this->modx->newObject(modChunk::class);
        $chunk->setCacheable(false);
        $chunk->setContent($this->render());

        return $chunk->process();
    }
}

return 'modDashboardWidgetButtons';

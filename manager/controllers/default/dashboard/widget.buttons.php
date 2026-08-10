<?php

use MODX\Revolution\modChunk;
use MODX\Revolution\modDashboardWidgetInterface;

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
        $this->modx->getSmarty();
        foreach ($this->widget->toArray() as $key => $value) {
            $this->modx->smarty->assign($key, $value);
        }

        return $this->controller->fetchTemplate('dashboard/buttons.tpl');
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

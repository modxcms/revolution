<?php

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
        $this->modx->getService('smarty', 'smarty.modSmarty');
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
        $chunk = $this->modx->newObject('modChunk');
        $chunk->setCacheable(false);
        $chunk->setContent($this->render());

        return $chunk->process();
    }
}

return 'modDashboardWidgetButtons';

<?php

namespace MODX\Processors\Element\Tv\Renders\Mgr\Input;

use MODX\modTemplateVarInputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class ListMultipleLegacy extends modTemplateVarInputRender
{
    public function getTemplate()
    {
        return 'element/tv/renders/input/list-multiple-legacy.tpl';
    }


    public function process($value, array $params = [])
    {
        $options = $this->getInputOptions();
        $items = [];

        $values = @explode('||', $value);
        foreach ($options as $option) {
            $opt = explode("==", $option);
            if (!isset($opt[1])) $opt[1] = $opt[0];
            $items[] = [
                'text' => htmlspecialchars($opt[0], ENT_COMPAT, 'UTF-8'),
                'value' => htmlspecialchars($opt[1], ENT_COMPAT, 'UTF-8'),
                'selected' => in_array($opt[1], $values),
            ];
        }
        $this->setPlaceholder('opts', $items);
    }
}
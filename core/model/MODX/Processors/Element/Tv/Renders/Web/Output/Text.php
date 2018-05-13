<?php

namespace MODX\Processors\Element\Tv\Renders\Web\Output;

use MODX\modTemplateVarOutputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class Text extends modTemplateVarOutputRender
{
    public function process($value, array $params = [])
    {
        $value = $this->tv->parseInput($value);
        if ($this->tv->get('type') == 'checkbox' || $this->tv->get('type') == 'listbox-multiple') {
            // remove delimiter from checkbox and listbox-multiple TVs
            $value = str_replace('||', '', $value);
        }
        $o = (string)$value;

        return $o;
    }
}
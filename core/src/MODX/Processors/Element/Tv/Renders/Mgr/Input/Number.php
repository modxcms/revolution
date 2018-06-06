<?php

namespace MODX\Processors\Element\Tv\Renders\Mgr\Input;

use MODX\modTemplateVarInputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class Number extends modTemplateVarInputRender
{
    public function getTemplate()
    {
        return 'element/tv/renders/input/number.tpl';
    }
}
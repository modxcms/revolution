<?php

namespace MODX\Processors\Element\Tv\Renders\Mgr\Input;

use MODX\modTemplateVarInputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class Textarea extends modTemplateVarInputRender
{
    public function getTemplate()
    {
        return 'element/tv/renders/input/textarea.tpl';
    }
}
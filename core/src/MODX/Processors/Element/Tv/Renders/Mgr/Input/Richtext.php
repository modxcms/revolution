<?php

namespace MODX\Processors\Element\Tv\Renders\Mgr\Input;

use MODX\modTemplateVarInputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class Richtext extends modTemplateVarInputRender
{
    public function process($value, array $params = [])
    {
        $which_editor = $this->modx->getOption('which_editor', null, '');
        $this->setPlaceholder('which_editor', $which_editor);
    }


    public function getTemplate()
    {
        return 'element/tv/renders/input/richtext.tpl';
    }
}
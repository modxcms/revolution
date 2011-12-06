<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderRichText extends modTemplateVarInputRender {
    public function process($value,array $params = array()) {
        $which_editor = $this->modx->getOption('which_editor',null,'');
        $this->setPlaceholder('which_editor',$which_editor);
    }
    public function getTemplate() {
        return 'element/tv/renders/input/richtext.tpl';
    }
}
return 'modTemplateVarInputRenderRichText';
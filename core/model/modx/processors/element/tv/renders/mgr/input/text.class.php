<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
if(!class_exists('modTemplateVarInputRenderText')){
    class modTemplateVarInputRenderText extends modTemplateVarInputRender {
        public function getTemplate() {
            return 'element/tv/renders/input/textbox.tpl';
        }
    }
} 
return 'modTemplateVarInputRenderText';

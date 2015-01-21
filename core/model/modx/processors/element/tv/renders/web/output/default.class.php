<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class modTemplateVarOutputRenderDefault extends modTemplateVarOutputRender {
    public function process($value,array $params = array()) {
        $value= $this->tv->parseInput($value);
        $o= (string) $value;
        return $o;
    }
}
return 'modTemplateVarOutputRenderDefault';

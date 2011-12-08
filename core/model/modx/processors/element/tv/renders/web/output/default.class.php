<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class modTemplateVarOutputRenderDefault extends modTemplateVarOutputRender {
    public function process($value,array $params = array()) {
        $value= $this->tv->parseInput($value);
        if ($this->tv->get('type') == 'checkbox' || $this->tv->get('type') == 'listbox-multiple') {
            // remove delimiter from checkbox and listbox-multiple TVs
            $value= str_replace('||', '', $value);
        }
        $o= (string) $value;
        return $o;
    }
}
return 'modTemplateVarOutputRenderDefault';
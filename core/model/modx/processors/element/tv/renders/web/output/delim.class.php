<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class modTemplateVarOutputRenderDelim extends modTemplateVarOutputRender {
    public function process($value,array $params = array()) {
        $value= $this->tv->parseInput($value, "||");
        $p= isset($params['delimiter']) ? $params['delimiter'] : ',';

        if ($p == "\\n") $p= "\n";
        return str_replace("||", $p, $value);
    }
}
return 'modTemplateVarOutputRenderDelim';
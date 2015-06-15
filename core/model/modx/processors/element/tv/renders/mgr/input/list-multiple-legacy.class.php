<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderListboxMultipleNoJs extends modTemplateVarInputRender {
    public function getTemplate() {
        return 'element/tv/renders/input/list-multiple-legacy.tpl';
    }
    public function process($value,array $params = array()) {
        $options = $this->getInputOptions();
        $items = array();

        $values = @explode('||', $value);
        foreach ($options as $option) {
            $opt = explode("==",$option);
            if (!isset($opt[1])) $opt[1] = $opt[0];
            $items[] = array(
                'text' => htmlspecialchars($opt[0],ENT_COMPAT,'UTF-8'),
                'value' => htmlspecialchars($opt[1],ENT_COMPAT,'UTF-8'),
                'selected' => in_array($opt[1], $values),
            );
        }
        $this->setPlaceholder('opts',$items);
    }
}
return 'modTemplateVarInputRenderListboxMultipleNoJs';
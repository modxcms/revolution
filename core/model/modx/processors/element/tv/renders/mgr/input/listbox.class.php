<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderListbox extends modTemplateVarInputRender {
    public function getTemplate() {
        return 'element/tv/renders/input/listbox-single.tpl';
    }
    public function process($value,array $params = array()) {
        $options = $this->getInputOptions();
        $items = array();

        $found = false;
        foreach ($options as $option) {
            $opt = explode("==",$option);
            if (!isset($opt[1])) $opt[1] = $opt[0];

            $selected = strcmp($opt[1], $value) == 0;
            if ($selected == 1) {
                $found = true;
            }

            $items[] = array(
                'text' => htmlspecialchars($opt[0],ENT_COMPAT,'UTF-8'),
                'value' => htmlspecialchars($opt[1],ENT_COMPAT,'UTF-8'),
                'selected' => $selected,
            );
        }

        if (!empty($value) && $found === false) {
            $items[] = array(
                'text' => htmlspecialchars($value,ENT_COMPAT,'UTF-8'),
                'value' => htmlspecialchars($value,ENT_COMPAT,'UTF-8'),
                'selected' => 1,
            );
        }

        $this->setPlaceholder('opts',$items);
    }
}
return 'modTemplateVarInputRenderListbox';
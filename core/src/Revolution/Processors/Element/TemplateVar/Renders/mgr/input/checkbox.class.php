<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modTemplateVarInputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderCheckbox extends modTemplateVarInputRender {
    public function getTemplate() {
        return 'element/tv/renders/input/checkbox.tpl';
    }
    public function process($value,array $params = []) {
        $value = explode("||",$value);

        $default = explode("||",$this->tv->get('default_text'));

        $options = $this->getInputOptions();

        $items = [];
        $defaults = [];
        $i = 0;
        foreach ($options as $option) {
            $opt = explode("==",$option);
            $checked = false;
            if (!isset($opt[1])) $opt[1] = $opt[0];

            /* set checked status */
            if (in_array($opt[1],$value)) {
                $checked = true;
            }
            /* add checkbox id to defaults if is a default value */
            if (in_array($opt[1],$default)) {
                $defaults[] = 'tv'.$this->tv->get('id').'-'.$i;
            }
            /* do escaping of strings, encapsulate in " so extjs/other systems can
             * utilize values correctly in their cast
             */
            if (preg_match('/^([-]?(0|0{1}[1-9]+[0-9]*|[1-9]+[0-9]*[\.]?[0-9]*))$/',$opt[1]) == 0) {
                $opt[1] = '"'.str_replace('"','\"',$opt[1]).'"';
            }

            $items[] = [
                'text' => htmlspecialchars($opt[0],ENT_COMPAT,'UTF-8'),
                'value' => $opt[1],
                'checked' => $checked,
            ];
            $i++;
        }
        $this->setPlaceholder('cbdefaults',implode(',',$defaults));
        $this->setPlaceholder('opts',$items);
    }
}
return 'modTemplateVarInputRenderCheckbox';

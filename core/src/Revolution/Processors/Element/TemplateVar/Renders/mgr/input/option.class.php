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
class modTemplateVarInputRenderOption extends modTemplateVarInputRender {
    public function process($value,array $params = []) {
        $default = $this->tv->get('default_text');

        // handles radio buttons
        $options = $this->getInputOptions();
        $items = [];
        $defaultIndex = '';
        $i = 0;
        foreach ($options as $option) {
            $opt = explode("==",$option);
            if (!isset($opt[1])) $opt[1] = $opt[0];
            $checked = false;

            /* set checked status */
            if (strcmp($opt[1],$value) == 0) {
                $checked = true;
            }
            /* set default value */
            if (strcmp($opt[1],$default) == 0) {
                $defaultIndex = 'tv'.$this->tv->get('id').'-'.$i;
                $this->tv->set('default_text',$defaultIndex);
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
        $this->setPlaceholder('opts',$items);
        $this->setPlaceholder('cbdefaults',$defaultIndex);
    }
    public function getTemplate() {
        return 'element/tv/renders/input/radio.tpl';
    }
}
return 'modTemplateVarInputRenderOption';

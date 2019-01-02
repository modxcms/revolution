<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderListboxMultiple extends modTemplateVarInputRender {
    public function getTemplate() {
        return 'element/tv/renders/input/listbox-multiple.tpl';
    }
    public function process($value,array $params = array()) {
        $value = explode("||",$value);

        $options = $this->getInputOptions();
        $items = array();
        foreach ($options as $option) {
            $opt = explode("==",$option);
            if (!isset($opt[1])) $opt[1] = $opt[0];
            $items[] = array(
                'text' => htmlspecialchars($opt[0],ENT_COMPAT,'UTF-8'),
                'value' => htmlspecialchars($opt[1],ENT_COMPAT,'UTF-8'),
                'selected' => in_array($opt[1],$value),
            );
        }

        // preserve the order of selected values
        $orderedItems = array();
        // loop trough the selected values
        foreach ($value as $val) {
            // find the corresponding option in the items array
            foreach ($items as $item => $values) {
                // if found, add it in the right order to the $orderItems array
                if ($values['value'] == $val) {
                    $orderedItems[] = $values;
                    // and remove it from the original $items array
                    unset($items[$item]);
                }
            }
        }
        // merge the correctly ordered items with the unselected remaining ones
        $items = array_merge($orderedItems, $items);

        $this->setPlaceholder('opts',$items);
    }
}
return 'modTemplateVarInputRenderListboxMultiple';

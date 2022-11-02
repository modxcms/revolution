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
class modTemplateVarInputRenderListboxMultiple extends modTemplateVarInputRender
{
    public function getTemplate()
    {
        return 'element/tv/renders/input/listbox-multiple.tpl';
    }
    public function process($value, array $params = [])
    {
        $savedValues = explode('||', $value);
        $options = $this->getInputOptions();

        $items = [];
        $selections = [];
        $optsValues = [];

        foreach ($options as $option) {
            $opt = explode('==', $option);
            if (!isset($opt[1])) {
                $opt[1] = $opt[0];
            }
            $optLabel = htmlspecialchars($opt[0], ENT_COMPAT, 'UTF-8');
            $optValue = htmlspecialchars($opt[1], ENT_COMPAT, 'UTF-8');

            /*
                Collect defined options values for later comparison to savedValues
                to determine if any custom user-entered values need to be accounted for.
            */
            $optsValues[] = $optValue;

            if (in_array($opt[1], $savedValues)) {
                $selections[] = [
                    'text' => $optLabel,
                    'value' => $optValue,
                    'selected' => 1
                ];
            } else {
                $items[] = [
                    'text' => $optLabel,
                    'value' => $optValue,
                    'selected' => 0
                ];
            }
        }

        // Ensure custom values are displayed when the listbox is editable
        if (isset($params['forceSelection']) && empty($params['forceSelection'])) {
            $customValues = array_diff($savedValues, $optsValues);
            if (!empty($customValues)) {
                $customData = [];
                foreach ($customValues as $customValue) {
                    $customValue = htmlspecialchars($customValue, ENT_COMPAT, 'UTF-8');
                    $customData[] = [
                        'text' => $customValue,
                        'value' => $customValue,
                        'selected' => 1
                    ];
                }
                $selections = array_merge($selections, $customData);
            }
        }
        $items = array_merge($selections, $items);

        $this->setPlaceholder('opts', $items);
    }
}
return 'modTemplateVarInputRenderListboxMultiple';

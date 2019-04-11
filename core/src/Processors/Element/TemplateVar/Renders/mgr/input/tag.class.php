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
 * @var string|array $value
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderTag extends modTemplateVarInputRender
{
    /**
     * @param string|array $value
     * @param array $params
     * @return mixed|void
     */
    public function process($value, array $params = array())
    {
        $value = is_array($value) ? $value : explode(',', $value);

        $options = array();

        foreach ($this->getInputOptions() as $option) {
            if (!$option) { continue; }
            $option = is_array($option) ? $option : explode('==', $option);
            if (count($option) === 1) {
                $option[] = $option[0];
            }
            list($inputOptionText, $inputOptionValue) = $option;
            $options[] = array(
                'value' => htmlspecialchars($inputOptionValue, ENT_COMPAT, 'UTF-8'),
                'text' => htmlspecialchars($inputOptionText,ENT_COMPAT,'UTF-8'),
                'checked' => in_array($inputOptionValue, $value, false),
            );
        }

        $this->setPlaceholder('options', $options);
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'element/tv/renders/input/tag.tpl';
    }
}

return 'modTemplateVarInputRenderTag';

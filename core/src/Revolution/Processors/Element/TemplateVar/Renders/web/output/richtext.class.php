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
 * @subpackage processors.element.tv.renders.mgr.output
 */
class modTemplateVarOutputRenderRichText extends modTemplateVarOutputRender {
    public function process($value,array $params = array()) {
        $id = 'tv'.$this->tv->get('name');
        $value= $this->tv->parseInput($value);
        $w= !empty($params['w']) ? $params['w'] : '100%';
        $h= !empty($params['h']) ? $params['h'] : '400px';
        $richtexteditor= $params['edt'] ? $params['edt'] : $this->modx->getOption('which_editor', null, '');
        $o= '<div class="MODX_RichTextWidget"><textarea id="' . $id . '" name="' . $id . '" style="width:' . $w . '; height:' . $h . ';">';
        $o .= htmlspecialchars($value);
        $o .= '</textarea></div>';
        $replace_richtext= array (
            $id
        );
        // setup editors
        if (!empty ($replace_richtext) && !empty ($richtexteditor)) {
            // invoke OnRichTextEditorInit event
            $evtOut= $this->modx->invokeEvent('OnRichTextEditorInit', array (
                'editor' => $richtexteditor,
                'elements' => $replace_richtext,
                'forfrontend' => 1,
                'width' => $w,
                'height' => $h
            ));
            if (is_array($evtOut))
                $o .= implode('', $evtOut);
        }
        return $o;
    }
}
return 'modTemplateVarOutputRenderRichText';

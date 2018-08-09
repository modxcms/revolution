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
class modTemplateVarOutputRenderHtmlTag extends modTemplateVarOutputRender {
    public function process($value,array $params = array()) {
        $value= $this->tv->parseInput($value, "||", "array");
        $tagid = !empty($params['tagid']) ? $params['tagid'] : '';
        $tagname = !empty($params['tagname']) ? $params['tagname'] : 'div';
        $id = 'tv'.$this->tv->get('name');
        $tagvalue = '';
        $o = '';
        /* Loop through a list of tags */
        for ($i = 0; $i < count($value); $i++) {
            $tagvalue = is_array($value[$i]) ? implode(' ', $value[$i]) : $value[$i];
            if (!$tagvalue) continue;

            $domId = $tagid ? $tagid : $id;
            $domId .= count($value) > 1 ? $i : '';

            $attributes = '';
            $attr = array(
                'id' => $domId, /* 'tv' already added to id */
                'class' => !empty($params['class']) ? $params['class'] : null,
                'style' => !empty($params['style']) ? $params['style'] : null,
            );
            foreach ($attr as $k => $v) $attributes.= ($v ? ' '.$k.'="'.$v.'"' : '');
            if (!empty($params['attrib'])) {
                $attributes .= ' '.$params['attrib'];
            }

            /* Output the HTML Tag */
            $o .= '<'.$tagname.rtrim($attributes).'>'.$tagvalue.'</'.$tagname.'>';
        }
        if (empty($o)) {
            $attributes = '';
            $attr = array(
                'class' => !empty($params['class']) ? $params['class'] : null,
                'style' => !empty($params['style']) ? $params['style'] : null,
            );
            foreach ($attr as $k => $v) $attributes.= ($v ? ' '.$k.'="'.$v.'"' : '');
            if (!empty($params['attrib'])) {
                $attributes .= ' '.$params['attrib']; /* add extra */
            }

            /* Output the HTML Tag */
            $o .= '<'.$tagname.rtrim($attributes).'>'.$tagvalue.'</'.$tagname.'>';
        }
        return $o;
    }
}
return 'modTemplateVarOutputRenderHtmlTag';

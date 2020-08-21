<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modTemplateVarOutputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class modTemplateVarOutputRenderImage extends modTemplateVarOutputRender {
    public function process($value,array $params = []) {
        $images= $this->tv->parseInput($value, '||', 'array');
        $o= '';
        foreach ($images as $image) {
            if (!is_array($image)) {
                $image= explode('==', $image);
            }
            $src= $image[0];
            if ($src) {
                $attributes = [];
                $attr = [
                    'src' => $src,
                    'id' => ($params['id'] ? $params['id'] : ''),
                    'class' => $params['class'],
                    'alt' => htmlspecialchars($params['alttext']),
                    'style' => $params['style']
                ];
                foreach ($attr as $k => $v) {
                    if (!empty($v)) {
                        $attributes[] = $k.'="'.$v.'"';
                    }
                }
                if (empty($attr['alt'])) $attributes[] = 'alt=""';
                $attributes = implode(' ',$attributes);
                $attributes .= ' '.$params['attributes'];

                /* Output the image with attributes */
                $o .= '<img '.rtrim($attributes).' />';
            }
        }
        return $o;
    }
}
return 'modTemplateVarOutputRenderImage';

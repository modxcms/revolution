<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */

$images= $this->parseInput($value, '||', 'array');
$o= '';
foreach ($images as $image) {
    if (!is_array($image)) {
        $image= explode('==', $image);
    }
    $src= $image[0];
    if ($src) {
        $attributes = '';
        $attr = array(
            'class' => $params['class'],
            'src' => $src,
            'id' => ($params['id'] ? $params['id'] : ''),
            'alt' => htmlspecialchars($params['alttext']),
            'style' => $params['style']
        );
        foreach ($attr as $k => $v) $attributes.= ($v ? ' '.$k.'="'.$v.'"' : '');
        $attributes .= ' '.$params['attributes'];

        /* Output the image with attributes */
        $o .= '<img'.rtrim($attributes).' />';
    }
}
return $o;
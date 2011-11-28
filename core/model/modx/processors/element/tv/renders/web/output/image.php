<?php
/**
 * @var modX $modx
 * @var modTemplateVar $this
 * @var array $params
 * @var string $value
 * 
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
        $attributes = array();
        $attr = array(
            'class' => $params['class'],
            'src' => $src,
            'id' => ($params['id'] ? $params['id'] : ''),
            'alt' => htmlspecialchars($params['alttext']),
            'style' => $params['style']
        );
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
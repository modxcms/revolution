<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */

$value= $this->parseInput($value, "||", "array");
$tagid = $params['tagid'];
$tagname = ($params['tagname'])? $params['tagname']:'div';
/* Loop through a list of tags */
for ($i = 0; $i < count($value); $i++) {
    $tagvalue = is_array($value[$i]) ? implode(' ', $value[$i]) : $value[$i];
    if (!$tagvalue) continue;

    $attributes = '';
    $attr = array(
        'id' => ($tagid ? $tagid : $id), /* 'tv' already added to id */
        'class' => $params['class'],
        'style' => $params['style'],
    );
    foreach ($attr as $k => $v) $attributes.= ($v ? ' '.$k.'="'.$v.'"' : '');
    $attributes .= ' '.$params['attrib']; /* add extra */

    /* Output the HTML Tag */
    $o .= '<'.$tagname.rtrim($attributes).'>'.$tagvalue.'</'.$tagname.'>';
}

return $o;
<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */

$value= $this->parseInput($value, "||", "array");
$tagid = $params['tagid'];
$tagname = ($params['tagname'])? $params['tagname']:'div';
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
        'class' => $params['class'],
        'style' => $params['style'],
    );
    foreach ($attr as $k => $v) $attributes.= ($v ? ' '.$k.'="'.$v.'"' : '');
    $attributes .= ' '.$params['attrib']; /* add extra */

    /* Output the HTML Tag */
    $o .= '<'.$tagname.rtrim($attributes).'>'.$tagvalue.'</'.$tagname.'>';
}
if (empty($o)) {
    $attributes = '';
    $attr = array(
        'class' => $params['class'],
        'style' => $params['style'],
    );
    foreach ($attr as $k => $v) $attributes.= ($v ? ' '.$k.'="'.$v.'"' : '');
    $attributes .= ' '.$params['attrib']; /* add extra */

    /* Output the HTML Tag */
    $o .= '<'.$tagname.rtrim($attributes).'>'.$tagvalue.'</'.$tagname.'>';
}
return $o;
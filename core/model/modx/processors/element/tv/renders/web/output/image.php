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
        $id= ($params['id'] ? 'id="' . $params['id'] . '"' : '');
        $alt= htmlspecialchars($params['alttext']);
        $class= $params['class'];
        $style= $params['style'];
        $attributes= $params['attrib'];
$o .=<<<EOD
<img {$id} src="{$src}" alt="{$alt}" class="{$class}" style="{$style}" {$attributes} />
EOD;
    }
}
return $o;
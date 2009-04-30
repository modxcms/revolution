<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */

$value= $this->parseInput($value, "||", "array");
$tagid= $params['tagid'];
$tagname= ($params['tagname']) ? $params['tagname'] : 'div';
for ($i= 0; $i < count($value); $i++) {
    $tagvalue= is_array($value[$i]) ? implode(" ", $value[$i]) : $value[$i];
    if (!$url)
        $url= $name;
    $o .= "<$tagname id='" . ($tagid ? $tagid : "tv" . $id) . "'" . ($params["class"] ? " class='" . $params["class"] . "'" : "") . ($params["style"] ? " style='" . $params["style"] . "'" : "") . ($params["attrib"] ? " " . $params["attrib"] : "") . ">" . $tagvalue . "</$tagname>";
}

return $o;
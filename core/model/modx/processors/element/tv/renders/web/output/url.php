<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */

$value= $this->parseInput($value, "||", "array");
for ($i= 0; $i < count($value); $i++) {
    list ($name, $url)= is_array($value[$i]) ? $value[$i] : explode("==", $value[$i]);

    if (!$url) $url= $name;

    if ($o) $o .= '<br />';

    $o .= "<a href='$url'" . " title='" . ($params["title"] ? $this->xpdo->db->escape($params["title"]) : $name) . "'" . ($params["class"] ? " class='" . $params["class"] . "'" : "") . ($params["style"] ? " style='" . $params["style"] . "'" : "") . ($params["target"] ? " target='" . $params["target"] . "'" : "") . ($params["attrib"] ? " " . $this->xpdo->db->escape($params["attrib"]) : "") . ">" . ($params["text"] ? $this->xpdo->db->escape($params["text"]) : $name) . "</a>";
}

return $o;
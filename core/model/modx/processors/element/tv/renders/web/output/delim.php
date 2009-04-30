<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */

$value= $this->parseInput($value, "||");
$p= $params['format'] ? $params['format'] : ",";

if ($p == "\\n") $p= "\n";
return str_replace("||", $p, $value);
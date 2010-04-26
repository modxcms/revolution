<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */


$value= $this->parseInput($value);
$timestamp= strtotime($value);
$p= $params['format'] ? $params['format'] : "%A %d, %B %Y";
$o= strftime($p, $timestamp);

return $o;
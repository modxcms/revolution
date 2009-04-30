<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */


$value= $this->parseInput($value);
// Check for MySQL style date - Adam Crownoble 8/3/2005
$date_match= '^([0-9]{2})-([0-9]{2})-([0-9]{4})\ ([0-9]{2}):([0-9]{2}):([0-9]{2})$';
$matches= array ();
if (strpos($value, '-') !== false && ereg($date_match, $value, $matches)) {
    $timestamp= mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[1], $matches[3]);
} else { // If it's not a MySQL style date, then use strtotime to figure out the date
    $timestamp= strtotime($value);
}
$p= $params['format'] ? $params['format'] : "%A %d, %B %Y";
$o= strftime($p, $timestamp);

return $o;
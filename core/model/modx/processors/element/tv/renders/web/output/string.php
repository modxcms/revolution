<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */

$value= $this->parseInput($value);
$format= strtolower($params['format']);

switch ($format) {
    case 'upper case':
        $o = strtoupper($value);
        break;
    case 'lower case':
        $o = strtolower($value);
        break;
    case 'sentence case':
        $o = ucfirst($value);
        break;
    case 'capitalize':
        $o = ucwords($value);
        break;
    default:
        $o = $value;
        break;
}
return $o;
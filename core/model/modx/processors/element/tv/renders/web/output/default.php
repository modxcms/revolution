<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */

$value= $this->parseInput($value);
if ($this->get('type') == 'checkbox' || $this->get('type') == 'listbox-multiple') {
    // remove delimiter from checkbox and listbox-multiple TVs
    $value= str_replace('||', '', $value);
}
$o= (string) $value;

return $o;
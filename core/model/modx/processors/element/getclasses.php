<?php
/**
 * Outputs a list of Element subclasses
 *
 * @package modx
 * @subpackage processors.element
 */

/* TODO: make this dynamic */
$classes = array(
    'modChunk',
    'modSnippet',
    'modPlugin',
    'modTemplateVar',
);

$list = array();
foreach ($classes as $class) {
    $el = array( 'name' => $class );

    $list[] = $el;
}

return $this->outputArray($list);
<?php
/**
 * Represents a relationship between a modContext and a modResource.
 *
 * @package modx
 * @todo Work this relationship into use in the manager and the logic of each
 * {@link modResource::process()} implementation.
 */
class modContextResource extends xPDOObject {
    function modContextResource(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
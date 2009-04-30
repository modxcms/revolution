<?php
/**
 * Represents a system or user-defined event that can be invoked.
 *
 * @package modx
 * @todo Remove deprecated variables, delegating to the plugins themselves which
 * will allow chained and dependent execution of sequenced plugins or even sets
 * of nested plugins
 */
class modEvent extends xPDOSimpleObject {
    function modEvent(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
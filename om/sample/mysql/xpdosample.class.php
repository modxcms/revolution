<?php
/**
 * This is a sample file containing classes used for testing various aspects of
 * an object model implemented with XPDO.
 * @package xpdo.om.sample.mysql
 */

/**
 * Represents a Sample xPDO class with various row types for testing purposes.
 * @see xpdosample.map.inc.php
 * @package xpdo.om.mysql
 * @subpackage example
 */
class xPDOSample extends xPDOSimpleObject {
    function xPDOSample(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
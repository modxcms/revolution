<?php
/**
 * @package modx
 * @subpackage registry.db
 */
class modDbRegisterTopic extends xPDOSimpleObject {
    function modDbRegisterTopic(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
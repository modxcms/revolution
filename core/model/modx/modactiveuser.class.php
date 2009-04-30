<?php
/**
 * Represents activity by a user in the system.
 *
 * @package modx
 */
class modActiveUser extends xPDOObject {
    function modActiveUser(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_cacheFlag= false;
    }
}
?>
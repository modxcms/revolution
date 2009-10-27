<?php
/**
 * Represents activity by a user in the system.
 *
 * @package modx
 */
class modActiveUser extends xPDOObject {
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_cacheFlag= false;
    }
}
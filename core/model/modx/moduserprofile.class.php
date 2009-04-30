<?php
/**
 * Represents extended user profile data.
 *
 * @package modx
 */
class modUserProfile extends xPDOSimpleObject {
    function modUserProfile(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
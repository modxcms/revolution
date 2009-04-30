<?php
/**
 * Represents a legacy web_user_attributes entity.
 * 
 * @deprecated 2007-09-20 For migration purposes only.
 * @package modx
 */
class modWebUserProfile extends xPDOSimpleObject {
    function modWebUserProfile(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
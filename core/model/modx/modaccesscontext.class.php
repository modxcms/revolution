<?php
/**
 * @package modx
 */
class modAccessContext extends modAccess {
    function modAccessContext(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
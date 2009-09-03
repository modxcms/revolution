<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modAccessActionDom extends modAccess {
    function modAccessActionDom(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
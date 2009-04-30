<?php
/**
 * @package modx
 * @subpackage registry.db.mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/moddbregistermessage.class.php');
class modDbRegisterMessage_mysql extends modDbRegisterMessage {
    function modDbRegisterMessage_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
<?php
/**
 * @package modx
 * @subpackage mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modaction.class.php');
class modAction_mysql extends modAction {
    function modAction_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
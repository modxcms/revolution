<?php
/**
 * @package modx
 * @subpackage transport.mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modtransportpackage.class.php');
class modTransportPackage_mysql extends modTransportPackage {
    function modTransportPackage_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
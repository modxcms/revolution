<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modxmlrpcresource.class.php');
class modXMLRPCResource_mysql extends modXMLRPCResource {
    function modXMLRPCResource_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
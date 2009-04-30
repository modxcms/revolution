<?php
/**
 * @package modx
 * @subpackage mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modworkspace.class.php');
class modWorkspace_mysql extends modWorkspace {
    function modWorkspace_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
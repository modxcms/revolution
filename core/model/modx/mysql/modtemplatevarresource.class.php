<?php
/**
 * @package modx
 * @subpackage mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modtemplatevarresource.class.php');
class modTemplateVarResource_mysql extends modTemplateVarResource {
    function modTemplateVarResource_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
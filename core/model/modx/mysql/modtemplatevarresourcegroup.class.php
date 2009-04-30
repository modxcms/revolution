<?php
/**
 * @package modx
 * @subpackage mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modtemplatevarresourcegroup.class.php');
class modTemplateVarResourceGroup_mysql extends modTemplateVarResourceGroup {
    function modTemplateVarResourceGroup_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>
<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modtemplatevartemplate.class.php');
class modTemplateVarTemplate_mysql extends modTemplateVarTemplate {
    function modTemplateVarTemplate_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
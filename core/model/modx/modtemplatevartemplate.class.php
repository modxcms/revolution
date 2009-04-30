<?php
/**
 * Represents a relationship between a template and a template variable.
 *
 * @package modx
 */
class modTemplateVarTemplate extends xPDOObject {
   function modTemplateVarTemplate(& $xpdo) {
      $this->__construct($xpdo); 
   }
   function __construct(& $xpdo) {
      parent :: __construct($xpdo);
   }
}
?>
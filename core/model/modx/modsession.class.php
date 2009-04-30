<?php
/**
 * Represents a client session managed by MODx.
 *
 * @package modx
 */
class modSession extends xPDOObject {
   function modSession(& $xpdo) {
      $this->__construct($xpdo);
   }
   function __construct(& $xpdo) {
      parent :: __construct($xpdo);
   }
}
?>
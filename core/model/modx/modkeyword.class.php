<?php
/**
 * Represents a keyword to be associated with a modResource.
 *
 * @deprecated 2007-09-19 - To be removed in 1.0; use {@link modResourceElement}
 * when it is implemented.
 * @package modx
 */
class modKeyword extends xPDOSimpleObject {
   function modKeyword(& $xpdo) {
      $this->__construct($xpdo);
   }
   function __construct(& $xpdo) {
      parent :: __construct($xpdo);
   }
}
?>
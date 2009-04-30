<?php
/**
 * Represents the legacy web_user_settings table.
 * 
 * @deprecated 2007-09-20 For migration purposes only.
 * @package modx
 */
class modWebUserSetting extends xPDOObject {
   function modWebUserSetting(& $xpdo) {
      $this->__construct($xpdo); 
   }
   function __construct(& $xpdo) {
      parent :: __construct($xpdo);
   }
}
?>
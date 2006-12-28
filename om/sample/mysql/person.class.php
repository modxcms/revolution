<?php
/**
 * This is an example file containing three closely related classes representing
 * a simple example object model implemented on XPDO.
 * @package xpdo.om.sample.mysql
 */

/**
 * Represents a Person.
 * @see person.map.inc.php
 * @package xpdo.om.mysql
 * @subpackage example
 */
class Person extends xPDOSimpleObject {
   function Person(& $xpdo) {
      $this->__construct($xpdo); 
   }
   function __construct(& $xpdo) {
      parent :: __construct($xpdo);
   }
}

/**
 * Represents a Phone number.
 * @see phone.map.inc.php
 * @package xpdo.om.mysql
 * @subpackage example
 */
class Phone extends xPDOSimpleObject {
   function Phone(& $xpdo) {
      $this->__construct($xpdo); 
   }
   function __construct(& $xpdo) {
      parent :: __construct($xpdo);
   }
}

/**
 * Represents a one to many relationship between a Person and a Phone.
 * @see personphone.map.inc.php
 * @package xpdo.om.mysql
 * @subpackage example
 */
class PersonPhone extends xPDOObject {
   function PersonPhone(& $xpdo) {
      $this->__construct($xpdo);
   }
   function _construct(& $xpdo) {
      parent :: __construct($xpdo);
   }
}
?>
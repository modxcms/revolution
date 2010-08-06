<?php
/**
 * This is an example file containing three closely related classes representing
 * a simple example object model implemented on xPDO.
 * @package sample
 */

/**
 * Represents a Person.
 * @see person.map.inc.php
 * @package sample
 */
class Person extends xPDOSimpleObject {}

/**
 * Represents a Phone number.
 * @see phone.map.inc.php
 * @package sample
 */
class Phone extends xPDOSimpleObject {}

/**
 * Represents a one to many relationship between a Person and a Phone.
 * @see personphone.map.inc.php
 * @package sample
 */
class PersonPhone extends xPDOObject {}

<?php
/**
 * Defines sample xPDO classes implemented for MySQL.
 *
 * This is an example file containing three closely related classes representing
 * a simple example object model implemented on xPDO for MySQL.
 *
 * @package sample.mysql
 */

/**
 * Include the required database-independent parent classes.
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../person.class.php');

/**
 * Represents a Person.
 * @see person.map.inc.php
 * @package sample.mysql
 */
class Person_mysql extends Person {}

/**
 * Represents a Phone number.
 * @see phone.map.inc.php
 * @package sample.mysql
 */
class Phone_mysql extends Phone {}

/**
 * Represents a one to many relationship between a Person and a Phone.
 * @see personphone.map.inc.php
 * @package sample.mysql
 */
class PersonPhone_mysql extends PersonPhone {}

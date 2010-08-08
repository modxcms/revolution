<?php
/**
 * Defines sample xPDO classes implemented for SQLite.
 *
 * This is an example file containing three closely related classes representing
 * a simple example object model implemented on xPDO for SQLite.
 *
 * @package sample.sqlite
 */

/**
 * Include the required database-independent parent classes.
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../person.class.php');

/**
 * Represents a Person.
 * @see person.map.inc.php
 * @package sample.sqlite
 */
class Person_sqlite extends Person {}

/**
 * Represents a Phone number.
 * @see phone.map.inc.php
 * @package sample.sqlite
 */
class Phone_sqlite extends Phone {}

/**
 * Represents a one to many relationship between a Person and a Phone.
 * @see personphone.map.inc.php
 * @package sample.sqlite
 */
class PersonPhone_sqlite extends PersonPhone {}

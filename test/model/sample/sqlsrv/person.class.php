<?php
/**
 * Defines sample xPDO classes implemented for sqlsrv.
 *
 * This is an example file containing three closely related classes representing
 * a simple example object model implemented on xPDO for sqlsrv.
 *
 * @package sample.sqlsrv
 */

/**
 * Include the required database-independent parent classes.
 */
require_once (dirname(dirname(__FILE__)) . '/person.class.php');

/**
 * Represents a Person.
 * @see person.map.inc.php
 * @package sample.sqlsrv
 */
class Person_sqlsrv extends Person {}

/**
 * Represents a Phone number.
 * @see phone.map.inc.php
 * @package sample.sqlsrv
 */
class Phone_sqlsrv extends Phone {}

/**
 * Represents a one to many relationship between a Person and a Phone.
 * @see personphone.map.inc.php
 * @package sample.sqlsrv
 */
class PersonPhone_sqlsrv extends PersonPhone {}

<?php
/**
 * This is a sample file containing classes used for testing.
 * @package sample.mysql
 */

/** Include the parent {@see xPDOSample} class */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../xpdosample.class.php');

/**
 * Represents a Sample xPDO class with various row types for testing purposes.
 * @see xpdosample.map.inc.php
 * @package sample.mysql
 */
class xPDOSample_mysql extends xPDOSample {}

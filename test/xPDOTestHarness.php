<?php
/**
 * Copyright 2010-2014 by MODX, LLC.
 *
 * This file is part of xPDO.
 *
 * xPDO is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xPDO is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xPDO; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package xpdo-test
 */
require_once dirname(__FILE__).'/xPDOTestCase.php';
require_once dirname(__FILE__).'/xPDO/xPDOSuite_AllTests.php';
/**
 * Main xPDO test harness.
 *
 * Use by running this in command-line:
 *
 * sh xpdotestharness.sh
 *
 * @package xpdo-test
 * @subpackage harness
 */
class xPDOTestHarness extends PHPUnit_Framework_TestSuite {
    /**
     * @var xPDO Static reference to xPDO instance.
     */
    public static $xpdo = null;
    /**
     * @var array Static reference to configuration array.
     */
    public static $properties = array();
    /**
	 * @var mixed Indicates the debug state of the test harness.
	 */
    public static $debug = false;

    protected function setUp() {
        $properties = array();
        include_once (dirname(dirname(__FILE__)) . '/xpdo/xpdo.class.php');
        include (dirname(__FILE__) . '/properties.inc.php');
        xPDOTestHarness::$properties = $properties;
        if (array_key_exists('debug', xPDOTestHarness::$properties)) {
        	xPDOTestHarness::$debug = xPDOTestHarness::$properties['debug'];
    	}
    }

    /**
     * Load all Test Suites for xPDO Test Harness.
     */
    public static function suite() {
        $suite = new xPDOTestHarness('xPDOHarness');
        $suite->addTest(xPDOSuite_AllTests::suite());

        return $suite;
    }

    /**
     * Grab a persistent instance of the xPDO class to share sample model data
     * across multiple tests and test suites.
     *
     * @param boolean $new Indicate if a new singleton should be created
     * @return xPDO An xPDO object instance.
     */
    public static function &getInstance($new = false) {
        if ($new || !is_object(xPDOTestHarness::$xpdo)) {
	        $driver= xPDOTestHarness::$properties['xpdo_driver'];
	        $xpdo= xPDO::getInstance(null, xPDOTestHarness::$properties["{$driver}_array_options"]);
	        if (is_object($xpdo)) {
		        $logLevel = array_key_exists('logLevel', xPDOTestHarness::$properties) ? xPDOTestHarness::$properties['logLevel'] : xPDO::LOG_LEVEL_WARN;
		        $logTarget = array_key_exists('logTarget', xPDOTestHarness::$properties) ? xPDOTestHarness::$properties['logTarget'] : (XPDO_CLI_MODE ? 'ECHO' : 'HTML');
		        $xpdo->setLogLevel($logLevel);
		        $xpdo->setLogTarget($logTarget);
		        if (!empty(xPDOTestHarness::$debug)) {
		            $xpdo->setDebug(xPDOTestHarness::$properties['debug']);
		        }
	            $xpdo->setPackage('sample', xPDOTestHarness::$properties['xpdo_test_path'] . 'model/');

		        xPDOTestHarness::$xpdo = $xpdo;
	        }
        }
	    return xPDOTestHarness::$xpdo;
    }
}

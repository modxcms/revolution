<?php
/**
 * Copyright 2010 by MODx, LLC.
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
require_once 'PHPUnit/Framework.php';
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
     * Load all Test Suites for xPDO Test Harness.
     */
    public static function suite() {
        $suite = new xPDOTestHarness('xPDOHarness');
        $suite->addTest(xPDOSuite_AllTests::suite());

        return $suite;
    }

    /**
     * Grab a persistent instance of the xPDO class to share connection data
     * across multiple tests and test suites.
     * 
     * @param array $options An array of configuration parameters.
     * @return xPDO An xPDO object instance.
     */
    public static function _getConnection($options = array()) {
        if (is_object(xPDOTestHarness::$xpdo)) return xPDOTestHarness::$xpdo;

        print 'Attempting to create xPDO singleton object.'."\n";
        
        $properties= array ();
        include_once (strtr(realpath(dirname(dirname(__FILE__))) . '/xpdo/xpdo.class.php', '\\', '/'));
        include (strtr(realpath(dirname(__FILE__)) . '/properties.inc.php', '\\', '/'));
        xPDOTestHarness::$properties= $properties;
        $driver= xPDOTestHarness::$properties['xpdo_driver'];
        $dsn= $driver . '_' . (array_key_exists('dsnProperty', $options) ? $options['dsnProperty'] : 'string_dsn_test');
        $xpdo= new xPDO(
                xPDOTestHarness::$properties[$dsn],
                xPDOTestHarness::$properties["{$driver}_string_username"],
                xPDOTestHarness::$properties["{$driver}_string_password"],
                xPDOTestHarness::$properties["{$driver}_array_options"],
                xPDOTestHarness::$properties["{$driver}_array_driverOptions"]
        );
        if (!is_object($xpdo)) {
            die('Could not connect to test database. Please create it and try again.');
        }

        if ($dsn == $driver . '_string_dsn_test') {
            $xpdo->setPackage('sample', strtr(realpath(dirname(__FILE__)) . '/model/', '\\', '/'));
        }
        if (array_key_exists('debug', xPDOTestHarness::$properties)) {
            $xpdo->setDebug(xPDOTestHarness::$properties['debug']); // set to true for debugging during tests only
        }
        $logLevel = array_key_exists('logLevel', xPDOTestHarness::$properties) ? xPDOTestHarness::$properties['logLevel'] : xPDO::LOG_LEVEL_WARN;
        $logTarget = array_key_exists('logTarget', xPDOTestHarness::$properties) ? xPDOTestHarness::$properties['logTarget'] : 'ECHO';
        $xpdo->setLogLevel($logLevel);
        $xpdo->setLogTarget($logTarget); // set to 'HTML' for running through browser
        if ($xpdo && $xpdo->connect()) {
            $driver = xPDOTestHarness::$properties['xpdo_driver'];
            $dsn = xPDOTestHarness::$properties[$driver . '_string_dsn_test'];
            echo 'Attempting to drop test database...';
            $response = $xpdo->getManager()->removeSourceContainer(xPDO::parseDSN($dsn));
            if ($response) {
                echo 'Test database dropped.'."\n";
            } else {
                echo 'Could not drop existing test database.'."\n";
            }
        } else {
            die('Could not connect to database.');
        }
        echo 'Attempting to create test database...';
        $created= $xpdo->getManager()->createSourceContainer();
        if (!$created) {
            die('Could not create test database.');
        } else {
            echo 'Test database created.'."\n";
        }

        $xpdo->setPackage('sample', strtr(realpath(dirname(__FILE__)) . '/model/', '\\', '/'));
        $debug = !empty(xPDOTestHarness::$properties['debug']);
        $xpdo->setDebug($debug);

        xPDOTestHarness::$xpdo = $xpdo;
        return $xpdo;
    }
}
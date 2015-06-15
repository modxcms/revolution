<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2014 by MODX, LLC.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package modx-test
 */
require_once dirname(__FILE__).'/MODxTestCase.php';
require_once dirname(__FILE__).'/MODxControllerTestCase.php';

/**
 * Main MODX test harness.
 *
 * Use by running this in command-line:
 *
 * sh modxtestharness.sh
 *
 * @package modx-test
 */
class MODxTestHarness {
    /** @var array $fixtures */
    protected static $fixtures = array();
    /** @var array $properties */
    protected static $properties = array();
    /** @var boolean $debug */
    protected static $debug = false;

    /**
     * Create or grab a reference to a static xPDO/modX instance.
     *
     * The instances can be reused by multiple tests and test suites.
     *
     * @param string $class A fixture class to get an instance of.
     * @param string $name A unique identifier for the fixture.
     * @param boolean $new
     * @param array $options An array of configuration options for the fixture.
     * @return object|null An instance of the specified fixture class or null on failure.
     */
    public static function &getFixture($class, $name, $new = false, array $options = array()) {
        if (!$new && array_key_exists($name, self::$fixtures) && self::$fixtures[$name] instanceof $class) {
            $fixture =& self::$fixtures[$name];
        } else {
            $properties = array();
            include_once dirname(dirname(dirname(__FILE__))) . '/core/model/modx/modx.class.php';
            include dirname(__FILE__) . '/properties.inc.php';
            self::$properties = $properties;
            if (array_key_exists('debug', self::$properties)) {
                self::$debug = (boolean) self::$properties['debug'];
            }

            $fixture = null;
            $driver= self::$properties['xpdo_driver'];
            switch ($class) {
                case 'modX':
                    if (!defined('MODX_REQP')) {
                        define('MODX_REQP',false);
                    }
                    if (!defined('MODX_CONFIG_KEY')) {
                        define('MODX_CONFIG_KEY', array_key_exists('config_key', self::$properties) ? self::$properties['config_key'] : 'test');
                    }
                    $fixture = new modX(
                        null,
                        self::$properties["{$driver}_array_options"]
                    );
                    if ($fixture instanceof modX) {
                        $logLevel = array_key_exists('logLevel', self::$properties) ? self::$properties['logLevel'] : modX::LOG_LEVEL_WARN;
                        $logTarget = array_key_exists('logTarget', self::$properties) ? self::$properties['logTarget'] : (XPDO_CLI_MODE ? 'ECHO' : 'HTML');
                        $fixture->setLogLevel($logLevel);
                        $fixture->setLogTarget($logTarget);
                        if (!empty(self::$debug)) {
                            $fixture->setDebug(self::$properties['debug']);
                        }

                        $fixture->initialize(self::$properties['context']);

                        $fixture->user = $fixture->newObject('modUser');
                        $fixture->user->set('id',$fixture->getOption('modx.test.user.id', null, 1));
                        $fixture->user->set('username',$fixture->getOption('modx.test.user.username', null, 'test'));

                        $fixture->getRequest();
                        $fixture->getParser();
                        $fixture->request->loadErrorHandler();
                    }
                    break;
                case 'xPDO':
                    $fixture = new xPDO(
                        self::$properties["{$driver}_string_dsn_test"],
                        self::$properties["{$driver}_string_username"],
                        self::$properties["{$driver}_string_password"],
                        self::$properties["{$driver}_array_options"],
                        self::$properties["{$driver}_array_driverOptions"]
                    );
                    if ($fixture instanceof xPDO) {
                        $logLevel = array_key_exists('logLevel', self::$properties) ? self::$properties['logLevel'] : xPDO::LOG_LEVEL_WARN;
                        $logTarget = array_key_exists('logTarget', self::$properties) ? self::$properties['logTarget'] : (XPDO_CLI_MODE ? 'ECHO' : 'HTML');
                        $fixture->setLogLevel($logLevel);
                        $fixture->setLogTarget($logTarget);
                        if (!empty(self::$debug)) {
                            $fixture->setDebug(self::$properties['debug']);
                        }
                    }
                    break;
                default:
                    $fixture = new $class($options);
                    break;
            }
            if ($fixture !== null && $fixture instanceof $class) {
                self::$fixtures[$name] = $fixture;
            } else {
                die("Error setting fixture {$name} of expected class {$class}.");
            }
        }
        return $fixture;
    }
}

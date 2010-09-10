<?php
/**
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
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
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/MODxTestCase.php';
require_once dirname(__FILE__).'/MODx/MODxSuite_AllTests.php';
/**
 * Main MODx test harness.
 *
 * Use by running this in command-line:
 *
 * sh modxtestharness.sh
 *
 * @package modx-test
 */
class MODxTestHarness extends PHPUnit_Framework_TestSuite {
    /**
     * @var modX Static reference to modX instance.
     */
    public static $modx = null;
    /**
     * @var array Static reference to configuration array.
     */
    public static $properties = array();

    /**
     * Load all Test Suites for xPDO Test Harness.
     */
    public static function suite() {
        $suite = new MODxTestHarness('MODxHarness');
        $suite->addTest(MODxSuite_AllTests::suite());

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
        if (is_object(MODxTestHarness::$modx)) return MODxTestHarness::$modx;

        print 'Attempting to create MODx singleton object.'."\n";
        
        /* include config.core.php */
        $properties = array();
        require_once strtr(realpath(dirname(dirname(dirname(__FILE__)))) . '/config.core.php','\\','/');
        require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
        require_once MODX_CORE_PATH.'model/modx/modx.class.php';
        include_once strtr(realpath(dirname(__FILE__)) . '/properties.inc.php','\\','/');
        $modx = new modX(null,$properties);
        $ctx = !empty($options['ctx']) ? $options['ctx'] : 'web';
        $modx->initialize($ctx);

        $debug = !empty($options['debug']);
        $modx->setDebug($debug);
        
        MODxTestHarness::$modx = $modx;
        return $modx;
    }
}
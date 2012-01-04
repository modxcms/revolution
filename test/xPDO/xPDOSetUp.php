<?php
/**
 * Copyright 2010-2012 by MODX, LLC.
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
/**
 * Tests related to setting up the test environment
 *
 * @package xpdo-test
 * @subpackage xpdo
 */
class xPDOSetUpTest extends PHPUnit_Framework_TestCase {
    /**
     * Test for a bogus false connection.
     *
     * @TODO Fix this, it seems to cause a timeout and a stall of PHPUnit.
     */
/*    public function testConnectionError() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $string_dsn= xPDOTestHarness::$properties[xPDOTestHarness::$properties['xpdo_driver'] . '_string_dsn_error'];
        $mypdo= new xPDO($string_dsn, "nonesuchuser", "nonesuchpass");
        $result= $mypdo->connect();
        // Should be an error set since we gave bogus info
        $this->assertTrue($result == false, "Connection was successful with bogus information.");
    }*/

    public function testInitialize() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $xpdo = xPDOTestHarness::getInstance(true);
        if (is_object($xpdo)) {
            $response = $xpdo->getManager()->removeSourceContainer(xPDO::parseDSN(xPDOTestHarness::$properties[xPDOTestHarness::$properties['xpdo_driver'] . '_string_dsn_test']));
            if ($response) {
                $xpdo= null;
            }
        } else {
            $xpdo = null;
        }
        $this->assertTrue($xpdo == null, "Test container exists and could not be removed for initialization");
    }

    /**
     * Verify test create database works.
     */
    public function testCreateSourceContainer() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $xpdo = xPDOTestHarness::getInstance(true);
        $created= $xpdo->getManager()->createSourceContainer();

        $this->assertTrue($created == true, "Could not create database.");
    }
}

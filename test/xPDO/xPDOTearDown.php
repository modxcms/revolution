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
/**
 * Tests related to cleaning up the test environment
 *
 * @package xpdo-test
 * @subpackage xpdo
 */
class xPDOTearDownTest extends PHPUnit_Framework_TestCase {
    /**
     * Ensure source container is not overwritten
     * By default, if the connection fails, it should just error out.
     * Should be an error set since we gave bogus info.
     */
    public function testDoNotOverwriteSourceContainer() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $xpdo = xPDOTestHarness::getInstance(true);
        $result = false;
        try {
            $xpdo->getManager();
            $driver = xPDOTestHarness::$properties['xpdo_driver'];
            $result = $xpdo->manager->createSourceContainer(
                xPDOTestHarness::$properties[$driver . '_string_dsn_test'],
                xPDOTestHarness::$properties[$driver . '_string_username'],
                xPDOTestHarness::$properties[$driver . '_string_password']
            );
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == false, "Error testing overwriting source container with createSourceContainer() method");
    }

    /**
     * Verify drop database works.
     */
    public function testRemoveSourceContainer() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $xpdo = xPDOTestHarness::getInstance(true);
        $success = false;
        if ($xpdo) {
            $driver = xPDOTestHarness::$properties['xpdo_driver'];
            $dsn = xPDOTestHarness::$properties[$driver . '_string_dsn_test'];
            $dsn = xPDO::parseDSN($dsn);
            $success = $xpdo->getManager()->removeSourceContainer($dsn);
        }
        $this->assertTrue($success, "Test container exists and could not be removed for initialization via xPDOManager->removeSourceContainer()");
    }
}

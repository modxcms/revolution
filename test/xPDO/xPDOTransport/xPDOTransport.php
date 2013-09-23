<?php
/**
 * Copyright 2010-2013 by MODX, LLC.
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
 * Tests related to xPDOTransport
 *
 * @package xpdo-test
 * @subpackage xpdozip
 */
class xPDOTransportTest extends xPDOTestCase {
    public function setUp() {
        parent::setUp();
        $this->xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
    }



    /**
     * Test xPDOTransport::satisfies()
     *
     * @param string $version
     * @param string $constraint
     * @param bool $expected
     * @dataProvider providerSatisfies
     */
    public function testSatisfies($version, $constraint, $expected) {
        $this->assertEquals($expected, xPDOTransport::satisfies($version, $constraint));
    }
    public function providerSatisfies() {
        return array(
            array('1.0.0', '~1.0', true),
            array('1.0.0', '~1.1', false),
            array('1.0.0', '>=0.9,<2.0', true),
            array('3.2.1', '3.*', true),
            array('3.2.1', '3.1.*', false),
            array('3.2.1', '3.2.*', true),
            array('3.2.1', '3.2.1', true),
            array('3.2.1', '3.2.2', false),
        );
    }

    /**
     * Test xPDOTransport::nextSignificantRelease()
     *
     * @param string $version
     * @param string $expected
     * @dataProvider providerNextSignificantRelease
     */
    public function testNextSignificantRelease($version, $expected) {
        $this->assertEquals($expected, xPDOTransport::nextSignificantRelease($version));
    }
    public function providerNextSignificantRelease() {
        return array(
            array('1.2.3', '1.3'),
            array('1.0', '2.0'),
            array('2.10.11-rc-12', '2.11'),
            array('0.10-rc1', '1.0'),
            array('0.10.1-pl', '0.11'),
        );
    }
}

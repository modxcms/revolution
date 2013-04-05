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
 * Extends the basic PHPUnit TestCase class to provide xPDO specific methods
 *
 * @package xpdo-test
 */
class xPDOTestCase extends PHPUnit_Framework_TestCase {
    /** @var xPDO $xpdo */
    public $xpdo = null;

    protected function setUp() {
    	   $this->xpdo = xPDOTestHarness::getInstance();
    }
    protected function tearDown() {
    	   $this->xpdo = null;
    }
}

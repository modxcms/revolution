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
require_once 'xPDO.php';
require_once 'xPDOObject.php';
require_once 'xPDOObjectSingleTableInheritance.php';
/**
 * Suite handling all xPDO-class centric tests.
 *
 * @package xpdo-test
 * @subpackage xpdo
 */
class xPDO_AllTests extends PHPUnit_Framework_TestSuite {
    public static function suite() {
        $suite = new xPDO_AllTests('xPDOClassTest');
        $suite->addTestSuite('xPDOTest');
        $suite->addTestSuite('xPDOObjectTest');
        $suite->addTestSuite('xPDOObjectSingleTableInheritanceTest');
        return $suite;
    }
}

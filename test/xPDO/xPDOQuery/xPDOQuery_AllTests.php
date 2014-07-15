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
require_once dirname(__FILE__).'/xPDOQuery.php';
require_once dirname(__FILE__).'/xPDOQuery_Having.php';
require_once dirname(__FILE__).'/xPDOQuery_Limit.php';
require_once dirname(__FILE__).'/xPDOQuery_SortBy.php';
/**
 * Suite handling all xPDOQuery-class centric tests.
 *
 * @package xpdo-test
 * @subpackage xpdoquery
 */
class xPDOQuery_AllTests extends PHPUnit_Framework_TestSuite
{
    public static function suite() {
        $suite = new xPDOQuery_AllTests('xPDOQueryClassTest');
        $suite->addTestSuite('xPDOQueryTest');
        $suite->addTestSuite('xPDOQueryHavingTest');
        $suite->addTestSuite('xPDOQueryLimitTest');
        $suite->addTestSuite('xPDOQuerySortByTest');
        return $suite;
    }
}

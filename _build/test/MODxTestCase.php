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
/**
 * Extends the basic PHPUnit TestCase class to provide MODx specific methods
 *
 * @package modx-test
 */
class MODxTestCase extends PHPUnit_Framework_TestCase {

    protected $modx = null;

    /**
     * Ensure all tests have a reference to the MODx object
     */
    public function setUp() {
        $this->modx =& MODxTestHarness::_getConnection();
    }

    /**
     * Remove reference at end of test case
     */
    public function tearDown() {
        $this->modx = null;
    }

    /**
     * Check a MODx return result for a success flag
     *
     * @param array $result The result response
     */
    public function checkForSuccess(&$result) {
        if ($result === true) return true;
        if (!is_array($result)) $result = $this->modx->fromJSON($result);
        $success = !empty($result['success']) && $result['success'] = true;
        return $success;
    }
}
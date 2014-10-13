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
/**
 * Extends the basic PHPUnit TestCase class to provide MODX specific methods
 *
 * @package modx-test
 */
abstract class MODxTestCase extends PHPUnit_Framework_TestCase {
    /**
     * @var modX $modx
     */
    public $modx = null;
    /**
     * @var bool
     */
    public $debug = false;

    /**
     * Ensure all tests have a reference to the MODX object
     */
    public function setUp() {
        $this->modx =& MODxTestHarness::getFixture('modX', 'modx');
        if ($this->modx->request) {
            $this->modx->request->loadErrorHandler();
            $this->modx->error->reset();
        }
        /* setup some basic test-environment options to allow us to simulate a site */
        $this->modx->setOption('http_host','unit.modx.com');
        $this->modx->setOption('base_url','/');
        $this->modx->setOption('site_url','http://unit.modx.com/');
    }

    /**
     * Remove reference at end of test case
     */
    public function tearDown() {}

    /**
     * Check a MODX return result for a success flag
     *
     * @param modProcessorResponse $result The result response
     * @return boolean
     */
    public function checkForSuccess(&$result) {
        if (empty($result) || !($result instanceof modProcessorResponse)) return false;
        return !$result->isError();
    }

    /**
     * Check a MODX processor response and return results
     *
     * @param string $result The response
     * @return array
     */
    public function getResults(&$result) {
        $response = ltrim(rtrim($result->response,')'),'(');
        $response = $this->modx->fromJSON($response);
        return !empty($response['results']) ? $response['results'] : array();
    }
}

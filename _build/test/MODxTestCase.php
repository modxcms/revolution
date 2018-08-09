<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
*/

/**
 * Extends the basic PHPUnit TestCase class to provide MODX specific methods
 *
 * @package modx-test
 */
abstract class MODxTestCase extends \PHPUnit\Framework\TestCase {
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

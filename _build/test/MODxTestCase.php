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
namespace MODX\Revolution;

use MODX\Revolution\Processors\ProcessorResponse;
use PHPUnit\Framework\TestCase;
use xPDO\xPDOException;

/**
 * Extends the basic PHPUnit TestCase class to provide MODX specific methods
 *
 * @package modx-test
 */
abstract class MODxTestCase extends TestCase {
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
     *
     * @throws xPDOException
     */
    public function setUp() {
        $this->modx = MODxTestHarness::getFixture(modX::class, 'modx');
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
     * @param ProcessorResponse $result The result response
     * @return boolean
     */
    public function checkForSuccess(&$result) {
        if (empty($result) || !($result instanceof ProcessorResponse)) return false;
        return !$result->isError();
    }

    /**
     * Check a MODX processor response and return results
     *
     * @param ProcessorResponse $result The response
     * @return array
     */
    public function getResults(&$result) {
        $response = ltrim(rtrim($result->response,')'),'(');
        $response = json_decode($response, true);
        return !empty($response['results']) ? $response['results'] : array();
    }
}

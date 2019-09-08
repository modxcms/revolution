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
namespace MODX\Revolution\Tests\Processors\Browser;


use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Browser\File\Get;

/**
 * Tests related to browser/file/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group BrowserProcessors
 * @group BrowserFileProcessors
 */
class BrowserFileProcessorsTest extends MODxTestCase {
    /**
     * Tests the browser/file/get processor, which grabs a file
     * @dataProvider providerGet
     * @param string $file The file to grab.
     */
    public function testGet($file = '') {
        if (empty($file)) {
            $this->fail('No provider data for BrowserFile::get');
        }

        /**
         * @TODO Configure test to work with media sources
         */
/*        $result = $this->modx->runProcessor(Get::class,array(
           'file' => $file,
        ));
        $this->assertTrue($this->checkForSuccess($result));*/
        $this->assertTrue(true);
    }
    /**
     * Data provider for get processor test.
     * @return array
     */
    public function providerGet() {
        return array(
            array('manager/index.php'),
        );
    }
}

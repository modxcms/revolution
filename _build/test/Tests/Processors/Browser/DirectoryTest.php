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


use Exception;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Browser\Directory\Create;
use MODX\Revolution\Processors\Browser\Directory\GetList;
use MODX\Revolution\Processors\Browser\Directory\Remove;
use MODX\Revolution\Processors\Browser\Directory\Update;

/**
 * Tests related to browser/directory/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group BrowserProcessors
 */
class BrowserDirectoryProcessorsTest extends MODxTestCase {
    public static function setUpBeforeClass() {
        @rmdir(MODX_BASE_PATH.'assets/test2/');
        @rmdir(MODX_BASE_PATH.'assets/test3/');
        @rmdir(MODX_BASE_PATH.'assets/test4/');
    }
    /**
     * Cleanup data after this test case.
     */
    public static function tearDownAfterClass() {
        @rmdir(MODX_BASE_PATH.'assets/test2/');
        @rmdir(MODX_BASE_PATH.'assets/test3/');
        @rmdir(MODX_BASE_PATH.'assets/test4/');
    }
    public function setUp() {
        parent::setUp();
    }

    /**
     * Tests the browser/directory/create processor, which creates a directory
     * @param string $dir
     * @dataProvider providerCreateDirectory
     */
    public function testCreateDirectory($dir = '') {
        if (empty($dir)) {
            $this->fail('Empty data set!');
            return;
        }

        $result = $this->modx->runProcessor(Create::class,array(
            'name' => $dir,
        ));
        if (empty($result)) {
            $this->fail('Could not load '. Create::class .' processor');
        }
        $s = $this->checkForSuccess($result);
        $this->assertTrue($s,'Could not create directory '.$dir.' in ' . Create::class . ' test: '.$result->getMessage());
    }
    /**
     * Data provider for create processor test.
     * @return array
     */
    public function providerCreateDirectory() {
        return array(
            array('assets/test2'),
            array('assets/test3'),
        );
    }

    /**
     * Tests the browser/directory/update processor, which renames a directory
     *
     * @TODO Fix this test.
     *
     * @param string $oldDirectory
     * @param string $newDirectory
     * @depends testCreateDirectory
     * @dataProvider providerUpdateDirectory
     */
    public function testUpdateDirectory($oldDirectory = '',$newDirectory = '') {
        $this->markTestSkipped(
            'The test is skipped - testUpdateDirectory.'
        );
        return;
        if (empty($oldDirectory) || empty($newDirectory)) return;

        $adir = $this->modx->getOption('base_path').$oldDirectory;
        @mkdir($adir);
        if (!file_exists($adir)) {
            $this->fail('Old directory could not be created for test: '.$adir);
        }

        $result = $this->modx->runProcessor(Update::class,array(
            'dir' => $oldDirectory,
            'name' => $this->modx->getOption('base_path').$newDirectory,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Update::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        $this->assertTrue($s,'Could not rename directory '.$oldDirectory.' to '.$newDirectory.' in ' . Update::class . ' test: '.$result->getMessage());
    }
    /**
     * Data provider for update processor test
     * @return array
     */
    public function providerUpdateDirectory() {
        return array(
            array('assets/test3/','assets/test4'),
        );
    }

    /**
     * Tests the browser/directory/remove processor, which removes a directory
     * @param string $dir
     * @dataProvider providerRemoveDirectory
     * @depends testCreateDirectory
     * @depends testUpdateDirectory
     */
    public function testRemoveDirectory($dir = '') {
        if (empty($dir)) return;
        $this->modx->setOption('filemanager_path','');
        $this->modx->setOption('filemanager_url','');
        $this->modx->setOption('rb_base_dir','');
        $this->modx->setOption('rb_base_url','');

        $adir = $this->modx->getOption('base_path').$dir;
        @mkdir($adir);
        if (!file_exists($adir)) {
            $this->fail('Old directory could not be created for test: '.$adir);
        }

        $result = $this->modx->runProcessor(Remove::class,array(
            'dir' => $dir,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Remove::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        $this->assertTrue($s,'Could not remove directory: `'.$dir.'`: '.$result->getMessage());
    }
    /**
     * Data provider for remove processor test.
     * @return array
     */
    public function providerRemoveDirectory() {
        return array(
            array('assets/test2'),
            array('assets/test4'),
        );
    }

    /**
     * Tests the browser/directory/getList processor
     *
     * @dataProvider providerGetDirectoryList
     * @param string $dir A string path to the directory to list.
     * @param boolean $shouldWork True if the directory list should not be empty.
     */
    public function testGetDirectoryList($dir,$shouldWork = true) {
        /** @var ProcessorResponse $response */
        $response = $this->modx->runProcessor(GetList::class,array(
            'id' => $dir,
        ));
        if (empty($response)) {
            $this->fail('Could not load '.GetList::class.' processor');
        }
        $dirs = json_decode($response->getResponse(), true);

        /* ensure correct test result */
        if ($shouldWork) {
            $success = !empty($dirs);
        } else {
            $success = empty($dirs);
        }
        $this->assertTrue($success,'Could not get list of files and dirs for '.$dir.' in '.GetList::class.' test');
    }
    /**
     * Test data provider for getList processor
     * @return array
     */
    public function providerGetDirectoryList() {
        return array(
            array('manager/',true),
            array('manager/assets',true),
            array('fakedirectory/',false),
        );
    }
}

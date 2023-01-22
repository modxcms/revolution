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
    /**
     * @beforeClass
     */
    public static function setUpFixturesBeforeClass() {
        @rmdir(MODX_BASE_PATH.'assets/test2/');
        @rmdir(MODX_BASE_PATH.'assets/test3/');
        @rmdir(MODX_BASE_PATH.'assets/test4/');
        @rmdir(MODX_BASE_PATH . 'assets/test5/');
        @rmdir(MODX_BASE_PATH . 'assets/test6/');
    }
    /**
     * Cleanup data after this test case.
     *
     * @afterClass
     */
    public static function tearDownFixturesAfterClass() {
        @rmdir(MODX_BASE_PATH.'assets/test2/');
        @rmdir(MODX_BASE_PATH.'assets/test3/');
        @rmdir(MODX_BASE_PATH.'assets/test4/');
        @rmdir(MODX_BASE_PATH . 'assets/test5/');
        @rmdir(MODX_BASE_PATH . 'assets/test6/');
    }
    /**
     * Setup fixtures before each test.
     *
     * @before
     */
    public function setUpFixtures() {
        parent::setUpFixtures();
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

        $result = $this->modx->runProcessor(Create::class, [
            'name' => $dir,
        ]);
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
        return [
            ['assets/test2'],
            ['assets/test3'],
        ];
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

        $result = $this->modx->runProcessor(Update::class, [
            'dir' => $oldDirectory,
            'name' => $this->modx->getOption('base_path').$newDirectory,
        ]);
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
        return [
            ['assets/test3/','assets/test4'],
        ];
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

        $adir = $this->modx->getOption('base_path').$dir;
        @mkdir($adir);
        if (!file_exists($adir)) {
            $this->fail('Old directory could not be created for test: '.$adir);
        }

        $result = $this->modx->runProcessor(Remove::class, [
            'dir' => $dir,
        ]);
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
        return [
            ['assets/test2'],
            ['assets/test4'],
        ];
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
        $response = $this->modx->runProcessor(GetList::class, [
            'id' => $dir,
        ]);
        if (empty($response)) {
            $this->fail('Could not load '.GetList::class.' processor');
        }

        /* ensure correct test result */
        if ($shouldWork) {
            $dirs = json_decode($response->getResponse(), true);
            $success = !empty($dirs);
        } else {
            $success = $response->isError();
        }
        $this->assertTrue($success,'Could not get list of files and dirs for '.$dir.' in '.GetList::class.' test');
    }
    /**
     * Test data provider for getList processor
     * @return array
     */
    public function providerGetDirectoryList() {
        return [
            ['manager/',true],
            ['manager/assets',true],
            ['fakedirectory/',false],
        ];
    }

    /**
     * Tests the browser/directory/getList processor with symlink present
     *
     * @dataProvider providerGetDirectoryListWithSymlink
     * @param string $dir A string path to the directory to list.
     */
    public function testGetDirectoryListWithSymlink($target, $link)
    {
        $dirtarget = $this->modx->getOption('base_path') . $target;
        $dirlink = $this->modx->getOption('base_path') . $link;
        @symlink($dirtarget, $dirlink);
        /** @var ProcessorResponse $response */
        $response = $this->modx->runProcessor(GetList::class, [
            'id' => 'assets',
        ]);
        if (empty($response)) {
            $this->fail('Could not load ' . GetList::class . ' processor');
        }
        $result = $response->getResponse();
        if (is_string($result)) {
            $dirs = json_decode($result, true);
        } else {
            $dirs = $result;
        }
        $success = !$response->isError() && is_array($dirs) && !empty($dirs);
        $this->assertTrue(
            $success,
            'Could not get list of files and dirs ignoring symlink in ' . GetList::class . ' test ' . __METHOD__
        );
    }
    /**
     * Test data provider for getList processor with symlink present
     * @return array
     */
    public function providerGetDirectoryListWithSymlink()
    {
        return [
            ['assets/test5', 'assets/test6'],
        ];
    }
}

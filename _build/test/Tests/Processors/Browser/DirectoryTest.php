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
 * Tests related to browser/directory/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group BrowserProcessors
 */
class BrowserDirectoryProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'browser/directory/';

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
        try {

        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
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

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'name' => $dir,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $this->assertTrue($s,'Could not create directory '.$dir.' in browser/directory/create test: '.$result->getMessage());
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
    public function tzestUpdateDirectory($oldDirectory = '',$newDirectory = '') {
        if (empty($oldDirectory) || empty($newDirectory)) return;

        $adir = $this->modx->getOption('base_path').$oldDirectory;
        @mkdir($adir);
        if (!file_exists($adir)) {
            $this->fail('Old directory could not be created for test: '.$adir);
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'update',array(
            'dir' => $oldDirectory,
            'name' => $this->modx->getOption('base_path').$newDirectory,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'update processor');
        }
        $s = $this->checkForSuccess($result);
        $this->assertTrue($s,'Could not rename directory '.$oldDirectory.' to '.$newDirectory.' in browser/directory/update test: '.$result->getMessage());
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
    public function tzestRemoveDirectory($dir = '') {
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

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'dir' => $dir,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
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
        /** @var modProcessorResponse $response */
        $response = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getlist',array(
            'id' => $dir,
        ));
        if (empty($response)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'getlist processor');
        }
        $dirs = $this->modx->fromJSON($response->getResponse());

        /* ensure correct test result */
        if ($shouldWork) {
            $success = !empty($dirs);
        } else {
            $success = empty($dirs);
        }
        $this->assertTrue($success,'Could not get list of files and dirs for '.$dir.' in browser/directory/getList test');
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

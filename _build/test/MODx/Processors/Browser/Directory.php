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
/**
 * Tests related to basic MODx class methods
 *
 * @package modx-test
 * @subpackage modx
 */
class BrowserDirectoryProcessors extends MODxTestCase {
    const PROCESSOR_LOCATION = 'browser/directory';

    /**
     * Tests the browser/directory/create processor
     * @dataProvider providerCreate
     */
    public function testCreate($dir = '') {
        if (empty($dir)) return false;
        $modx = MODxTestHarness::_getConnection();
        
        $modx->setOption('filemanager_path','');
        $modx->setOption('filemanager_url','');
        $modx->setOption('rb_base_dir','');
        $modx->setOption('rb_base_url','');

        try {
            $_POST['name'] = $dir;
            $result = $modx->executeProcessor(array(
                'location' => BrowserDirectoryProcessors::PROCESSOR_LOCATION,
                'action' => 'create',
            ));
        } catch (Exception $e) {
            $modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $s = $this->checkForSuccess($modx,$result);
        $this->assertTrue($s,'Could not create directory '.$dir.' in browser/directory/create test.');
    }
    /**
     * Data provider for create processor test.
     * 
     * Note: Make sure this data is synced with the providerCreate method.
     */
    public function providerCreate() {
        return array(
            array('assets2'),
            array('assets3/'),
        );
    }

    /**
     * @dataProvider providerCreate
     * @param string $dir
     */
    public function testRemove($dir = '') {
        if (empty($dir)) return false;
        $modx = MODxTestHarness::_getConnection();

        $modx->setOption('filemanager_path','');
        $modx->setOption('filemanager_url','');
        $modx->setOption('rb_base_dir','');
        $modx->setOption('rb_base_url','');

        try {
            $_POST['dir'] = $dir;
            $result = $modx->executeProcessor(array(
                'location' => BrowserDirectoryProcessors::PROCESSOR_LOCATION,
                'action' => 'remove',
            ));
        } catch (Exception $e) {
            $modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $s = $this->checkForSuccess($modx,$result);
        $this->assertTrue($s);
    }
    /**
     * Data provider for remove processor test.
     *
     * Note: Make sure this data is synced with the providerCreate method.
     */
    public function providerRemove() {
        return array(
            array('assets2'),
            array('assets3/'),
        );
    }

    /**
     * Tests the browser/directory/getList processor
     * 
     * @dataProvider providerGetList
     * @param string $dir A string path to the directory to list.
     * @param boolean $shouldWork True if the directory list should not be empty.
     * @param string $filemanager_path A custom filemanager_path
     * @param string $filemanager_url A custom filemanager_url
     */
    public function testGetList($dir,$shouldWork = true,$filemanager_path = '',$filemanager_url = '') {
        $modx = MODxTestHarness::_getConnection();

        $modx->setOption('filemanager_path',$filemanager_path);
        $modx->setOption('filemanager_url',$filemanager_url);
        $modx->setOption('rb_base_dir','');
        $modx->setOption('rb_base_url','');
        
        $_POST['id'] = $dir;
        $result = $modx->executeProcessor(array(
            'location' => BrowserDirectoryProcessors::PROCESSOR_LOCATION,
            'action' => 'getList',
        ));
        if (!is_array($result)) $result = $modx->fromJSON($result);

        /* ensure correct test result */
        $success = $shouldWork ?
            empty($result['success']) || $result['success'] == true
            : isset($result['success']) && $result['success'] == false;
        $this->assertTrue($success);
    }
    /**
     * Test data provider for getList processor
     */
    public function providerGetList() {
        $modx = MODxTestHarness::_getConnection();
        return array(
            array('manager/',true),
            array('manager/assets',true),
            array('fakedirectory/',false),
            array('assets',true,MODX_BASE_PATH.'manager/',MODX_BASE_URL.'manager/'),
        );
    }
}
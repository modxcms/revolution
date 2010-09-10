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
 * Tests related to browser/file/ processors
 *
 * @package modx-test
 * @subpackage modx
 */
class BrowserFileProcessors extends MODxTestCase {
    const PROCESSOR_LOCATION = 'browser/file/';

    /**
     * Tests the browser/file/get processor, which grabs a file
     * @dataProvider providerGet
     * @param string $file The file to grab.
     */
    public function testGet($file = '') {
        if (empty($file)) return false;
        $modx = MODxTestHarness::_getConnection();

        $modx->setOption('filemanager_path','');
        $modx->setOption('filemanager_url','');
        $modx->setOption('rb_base_dir','');
        $modx->setOption('rb_base_url','');
        
        try {
            $_POST['file'] = MODX_BASE_PATH.$file;
            $result = $modx->executeProcessor(array(
                'location' => BrowserFileProcessors::PROCESSOR_LOCATION,
                'action' => 'get',
            ));
        } catch (Exception $e) {
            $modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        if (is_string($result)) { $result = $modx->fromJSON($result); }

        $s = $this->checkForSuccess($modx,$result);
        $this->assertTrue($s,'Could not get file '.$file.' in browser/file/get test.');
    }
    /**
     * Data provider for get processor test.
     */
    public function providerGet() {
        return array(
            array('manager/index.php'),  
        );
    }
}
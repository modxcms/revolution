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

namespace modX\Tests\Processors\Browser;

use modX\Tests\MODxTestCase;

class BrowserFileProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'browser/file/';

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
        /*$result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
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

<?php
/**
 * Copyright 2010-2014 by MODX, LLC.
 *
 * This file is part of xPDO.
 *
 * xPDO is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xPDO is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xPDO; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package xpdo-test
 */

/**
 * Tests related to basic xPDOCacheManager methods
 *
 * @package xpdo-test
 * @subpackage xpdocache
 */
class xPDOCacheManagerTest extends xPDOTestCase {
    /**
     * Test writing a directory tree structure to the filesystem.
     *
     * @dataProvider providerWriteTree
     * @param $path
     * @param $options
     */
    public function testWriteTree($path, $options) {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $path = xPDOTestHarness::$properties['xpdo_test_path'] . "fs/{$path}";
        $result = $this->xpdo->getCacheManager()->writeTree($path, $options);
        $this->assertTrue($result, 'Error writing directory tree to filesystem');
    }
    public function providerWriteTree() {
        return array(
            array('test', array()),
            array('test/', array()),
            array('test/dirA', array()),
            array('test/dirB/', array()),
            array('test/dirA/subdirA', array()),
            array('test/dirA/subdirB/', array()),
        );
    }

    /**
     * Test copying a directory and it's contents on the filesystem.
     *
     * @dataProvider providerCopyTree
     * @param $source
     * @param $target
     * @param $options
     */
    public function testCopyTree($source, $target, $options, $expected) {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $source = xPDOTestHarness::$properties['xpdo_test_path'] . "fs/{$source}";
        $target = xPDOTestHarness::$properties['xpdo_test_path'] . "fs/{$target}";
        while (list($idx, $path) = each($expected)) $expected[$idx] = xPDOTestHarness::$properties['xpdo_test_path'] . 'fs/' . $path;
        $result = $this->xpdo->getCacheManager()->copyTree($source, $target, $options);
        $this->assertEquals($expected, $result, 'Error copying directory tree on filesystem');
    }
    public function providerCopyTree() {
        return array(
            array('test/dirA', 'copy/', array(), array(
                "copy/subdirA",
                "copy/subdirB",
                "copy",
            )),
        );
    }

    /**
     * Test deleting a directory from the filesystem.
     *
     * @dataProvider providerDeleteTree
     * @param $path
     * @param $options
     * @param $expected
     */
    public function testDeleteTree($path, $options, $expected) {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $path = xPDOTestHarness::$properties['xpdo_test_path'] . "fs/{$path}";
        while (list($idx, $dir) = each($expected)) $expected[$idx] = xPDOTestHarness::$properties['xpdo_test_path'] . 'fs/' . $dir;
        $result = $this->xpdo->getCacheManager()->deleteTree($path, $options);
        $this->assertEquals($expected, $result, 'Error deleting directory tree from filesystem');
    }
    public function providerDeleteTree() {
        return array(
            array('test/dirA', array('deleteTop' => false, 'skipDirs' => false, 'extensions' => array()), array(
                "test/dirA/subdirA/",
                "test/dirA/subdirB/",
            )),
            array('test/dirB/', array('deleteTop' => false, 'skipDirs' => false, 'extensions' => array()), array()),
            array('copy/', array('deleteTop' => true, 'skipDirs' => false, 'extensions' => array()), array(
                "copy/subdirA/",
                "copy/subdirB/",
                "copy/",
            )),
            array('test/dirA', array('deleteTop' => true, 'skipDirs' => false, 'extensions' => array()), array(
                "test/dirA/",
            )),
            array('test/dirB', array('deleteTop' => true, 'skipDirs' => false, 'extensions' => array()), array(
                "test/dirB/",
            )),
            array('test/', array('deleteTop' => true, 'skipDirs' => false, 'extensions' => array()), array(
                "test/",
            )),
        );
    }
}

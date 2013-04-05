<?php
/**
 * Copyright 2010-2013 by MODX, LLC.
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
 * Tests related to xPDOZip methods
 *
 * @package xpdo-test
 * @subpackage xpdozip
 */
class xPDOZipTest extends xPDOTestCase {
    public static function setUpBeforeClass() {
        $xpdo = xPDOTestHarness::getInstance();

        $zipPath = xPDOTestHarness::$properties['xpdo_test_path'] . "fs/zip/";
        $xpdo->getCacheManager()->writeTree($zipPath);
        $xpdo->getCacheManager()->writeTree("{$zipPath}1/");
        $xpdo->getCacheManager()->writeTree("{$zipPath}1/a/");
        $xpdo->getCacheManager()->writeTree("{$zipPath}1/b/");
        $xpdo->getCacheManager()->writeTree("{$zipPath}1/c/");
        $xpdo->getCacheManager()->writeTree("{$zipPath}2/");
        $xpdo->getCacheManager()->writeFile("{$zipPath}2/a", "### placeholder file ###");
        $xpdo->getCacheManager()->writeFile("{$zipPath}2/b", "### placeholder file ###");
        $xpdo->getCacheManager()->writeFile("{$zipPath}2/c", "### placeholder file ###");
        $xpdo->getCacheManager()->writeTree("{$zipPath}3/");

        $unzipPath = xPDOTestHarness::$properties['xpdo_test_path'] . "fs/unzip";
        $xpdo->getCacheManager()->writeTree($unzipPath);
    }

    public static function tearDownAfterClass() {
        $xpdo = xPDOTestHarness::getInstance();
        $paths = array(
            xPDOTestHarness::$properties['xpdo_test_path'] . "fs/zip/",
            /*xPDOTestHarness::$properties['xpdo_test_path'] . "fs/unzip/"*/
        );
        foreach ($paths as $path) {
            $xpdo->getCacheManager()->deleteTree($path, array('deleteTop' => true, 'skipDirs' => false, 'extensions' => array()));
        }
        $files = array(
            xPDOTestHarness::$properties['xpdo_test_path'] . "fs/test-1.zip",
            xPDOTestHarness::$properties['xpdo_test_path'] . "fs/test-2.zip",
            xPDOTestHarness::$properties['xpdo_test_path'] . "fs/test-3.zip"
        );
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }
    }

    /**
     * Test creating and packing files/dirs into a ZipArchive
     *
     * @dataProvider providerPackArchive
     * @param $source
     * @param $archive
     * @param $options
     * @param $packOptions
     * @param $expected
     */
    public function testPackArchive($source, $archive, $options, $packOptions, $expected) {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result = false;
        $sourcePath = xPDOTestHarness::$properties['xpdo_test_path'] . "fs/zip/{$source}";
        $archivePath = xPDOTestHarness::$properties['xpdo_test_path'] . "fs/{$archive}";
        try {
            $this->xpdo->loadClass('compression.xPDOZip', XPDO_CORE_PATH, true, true);
            $zip = new xPDOZip($this->xpdo, $archivePath, $options);
            $result = $zip->pack($sourcePath, $packOptions);
            while (list($idx, $entry) = each($result)) $result[$idx] = str_replace($sourcePath, $source, $entry);
            $this->xpdo->log(xPDO::LOG_LEVEL_INFO, "Pack results for {$archive}: " . print_r($result, true));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertEquals($expected, $result, "Error packing xPDOZip archive {$archive} from {$source}");
//        $this->assertTrue(file_exists($archivePath), "Error creating xPDOZip archive at {$archive}");
    }
    public function providerPackArchive() {
        return array(
            array('1/', 'test-1.zip', array('create' => true, 'overwrite' => true), array('zip_target' => '1/'), array(
                '1/' => 'Successfully added directory 1/ from 1/',
                '1/a/' => 'Successfully added directory 1/a/ from 1/a/',
                '1/b/' => 'Successfully added directory 1/b/ from 1/b/',
                '1/c/' => 'Successfully added directory 1/c/ from 1/c/'
            )),
            array('2/', 'test-2.zip', array('create' => true, 'overwrite' => true), array('zip_target' => '2/'), array(
                '2/' => 'Successfully added directory 2/ from 2/',
                '2/a' => 'Successfully packed 2/a from 2/a',
                '2/b' => 'Successfully packed 2/b from 2/b',
                '2/c' => 'Successfully packed 2/c from 2/c'
            )),
        );
    }

    /**
     * Test unpacking files/dirs from a ZipArchive
     *
     * @dataProvider providerUnpackArchive
     * @depends testPackArchive
     * @param $target
     * @param $archive
     * @param $options
     * @param $unpackOptions
     * @param $expected
     */
    public function testUnpackArchive($target, $archive, $options, $unpackOptions, $expected) {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result = false;
        $targetPath = xPDOTestHarness::$properties['xpdo_test_path'] . "fs/unzip/{$target}";
        $archivePath = xPDOTestHarness::$properties['xpdo_test_path'] . "fs/{$archive}";
        try {
            $this->xpdo->loadClass('compression.xPDOZip', XPDO_CORE_PATH, true, true);
            $archive = new xPDOZip($this->xpdo, $archivePath, $options);
            $result = $archive->unpack($targetPath, $unpackOptions);
            $this->xpdo->log(xPDO::LOG_LEVEL_INFO, "Unpack results for {$archivePath}: " . print_r($result, true));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertEquals($expected, $result, "Error unpacking xPDOZip archive {$archivePath} to target {$targetPath}");
    }
    public function providerUnpackArchive() {
        return array(
            array('', 'test-1.zip', array(), array(), true),
            array('', 'test-2.zip', array(), array(), true),
        );
    }
}
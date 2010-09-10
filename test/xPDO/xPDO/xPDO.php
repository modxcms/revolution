<?php
/**
 * Tests related to basic xPDO methods
 *
 * @package xpdo-test
 * @subpackage xpdo
 */
class xPDOTest extends xPDOTestCase {
    /**
     * Verify drop database works.
     */
    public function testRemoveSourceContainer() {
        $success = false;
        $xpdo = xPDOTestHarness::_getConnection();
        if ($xpdo && $xpdo->connect()) {
            $driver = xPDOTestHarness::$properties['xpdo_driver'];
            $dsn = xPDOTestHarness::$properties[$driver . '_string_dsn_test'];
            $dsn = xPDO::parseDSN($dsn);
            $success = $xpdo->getManager()->removeSourceContainer($dsn);
        }
        $this->assertTrue($success, "Test container exists and could not be removed for initialization via xPDOManager->removeSourceContainer()");
    }

    /**
     * Verify test create database works.
     *
     * @depends testRemoveSourceContainer
     */
    public function testCreateSourceContainer() {
        $xpdo = xPDOTestHarness::_getConnection();
        $created= $xpdo->getManager()->createSourceContainer();

        $this->assertTrue($created == true, "Could not create database.");
    }

    /**
     * Verify xPDO::connect works.
     */
    public function testConnect() {
        $this->xpdo = xPDOTestHarness::_getConnection();
        print __METHOD__ ." - Testing for xPDO Connection. \n";
        $connect = $this->xpdo->connect();
        $this->assertTrue($connect,'xPDO could not connect via xpdo->connect().');
    }

    /**
     * Test for a bogus false connection.
     *
     * @TODO Fix this, it seems to cause a timeout and a stall of PHPUnit.
     */
    /*
    public function testConnectionError() {
        $string_dsn= xPDOTestHarness::$properties[xPDOTestHarness::$properties['xpdo_driver'] . '_string_dsn_error'];

        $mypdo= new xPDO($string_dsn, "nonesuchuser", "nonesuchpass");
        $result= $mypdo->connect();
        // Should be an error set since we gave bogus info
        $this->assertTrue($result == false, "Connection was successful with bogus information.");
    }*/

    /**
     * Test table creation.
     *
     * @depends testCreateSourceContainer
     */
    public function testCreateObjectContainer() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $xpdo->getManager();
            $result[] = $xpdo->manager->createObjectContainer('Person');
            $result[] = $xpdo->manager->createObjectContainer('Phone');
            $result[] = $xpdo->manager->createObjectContainer('PersonPhone');
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!array_search(false, $result, true), 'Error creating tables.');
    }

    /**
     * Ensure source container is not overwritten
     * By default, if the connection fails, it should just error out.
     * Should be an error set since we gave bogus info.
     */
    public function testDoNotOverwriteSourceContainer() {
        $result = false;
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $xpdo->getManager();
            $driver= xPDOTestHarness::$properties['xpdo_driver'];
            $result= $xpdo->manager->createSourceContainer(
                    xPDOTestHarness::$properties[$driver . '_string_dsn_test'],
                    xPDOTestHarness::$properties[$driver . '_string_username'],
                    xPDOTestHarness::$properties[$driver . '_string_password']
            );
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == false, "Error testing overwriting source container with createSourceContainer() method");
    }

    /**
     * Tests xPDO::escape
     */
    public function testEscape() {
        $xpdo = xPDOTestHarness::_getConnection();

        $correct = 'test';
        $correct = trim($correct, $xpdo->_escapeCharOpen . $xpdo->_escapeCharClose);
        $correct = $xpdo->_escapeCharOpen . $correct . $xpdo->_escapeCharClose;

        $eco = $xpdo->_escapeCharOpen;
        $ecc = $xpdo->_escapeCharClose;
        $this->assertEquals($correct,$xpdo->escape('test'),'xpdo->escape() did not correctly escape.');
        $this->assertEquals($correct,$xpdo->escape($eco.'test'),'xpdo->escape() did not strip the beginning escape character before escaping.');
        $this->assertEquals($correct,$xpdo->escape($eco.'test'.$ecc),'xpdo->escape() did not strip the beginning and end escape character before escaping.');
        $this->assertEquals($correct,$xpdo->escape('test'.$ecc),'xpdo->escape() did not strip the end escape character before escaping.');
    }

    /**
     * Test xPDO::escSplit
     */
    public function testEscSplit() {
        $xpdo = xPDOTestHarness::_getConnection();
        
        $str = '1,2,3';
        $result = xPDO::escSplit(',',$str,$xpdo->_escapeCharOpen);
        $this->assertTrue(is_array($result),'xPDO::escSplit did not return an array.');
        $this->assertEquals(3,count($result),'xPDO::escSplit did not return the correct number of indices.');
    }

    /**
     * Test xPDO::fromJSON
     */
    public function testFromJson() {
        $xpdo = xPDOTestHarness::_getConnection();

        $json = '{"key":"value","nested":{"foo":"123","another":"test"}}';
        $result = $xpdo->fromJSON($json);
        $this->assertTrue(is_array($result),'xpdo->fromJSON() did not return an array.');
    }

    /**
     * Test xPDO::toJSON
     */
    public function testToJson() {
        $xpdo = xPDOTestHarness::_getConnection();

        $array = array(
            'key' => 'value',
            'nested' => array(
                'foo' => '123',
                'another' => 'test',
            ),
        );
        $result = $xpdo->toJSON($array);
        $this->assertTrue(is_string($result),'xpdo->fromJSON() did not return an array.');
    }

    /**
     * Test xPDO::getManager
     */
    public function testGetManager() {
        $xpdo = xPDOTestHarness::_getConnection();

        $manager = $xpdo->getManager();
        $success = is_object($manager) && $manager instanceof xPDOManager;
        $this->assertTrue($success,'xpdo->getManager did not return an xPDOManager instance.');
    }

    /**
     * Test xPDO::getDriver
     */
    public function testGetDriver() {
        $xpdo = xPDOTestHarness::_getConnection();

        $driver = $xpdo->getDriver();
        $success = is_object($driver) && $driver instanceof xPDODriver;
        $this->assertTrue($success,'xpdo->getDriver did not return an xPDODriver instance.');
    }

    /**
     * Test xPDO::getCacheManager
     */
    public function testGetCacheManager() {
        $xpdo = xPDOTestHarness::_getConnection();

        $cacheManager = $xpdo->getCacheManager();
        $success = is_object($cacheManager) && $cacheManager instanceof xPDOCacheManager;
        $this->assertTrue($success,'xpdo->getCacheManager did not return an xPDOCacheManager instance.');
    }

    /**
     * Test xPDO::getCachePath
     */
    public function testGetCachePath() {
        $xpdo = xPDOTestHarness::_getConnection();

        $cachePath = $xpdo->getCachePath();
        $this->assertEquals($cachePath,XPDO_CORE_PATH.'cache/','xpdo->getCachePath() did not return the correct cache path.');
    }
}
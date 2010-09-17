<?php
/**
 * Copyright 2010 by MODx, LLC.
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
        if ($this->xpdo && $this->xpdo->connect()) {
            $driver = xPDOTestHarness::$properties['xpdo_driver'];
            $dsn = xPDOTestHarness::$properties[$driver . '_string_dsn_test'];
            $dsn = xPDO::parseDSN($dsn);
            $success = $this->xpdo->getManager()->removeSourceContainer($dsn);
        }
        $this->assertTrue($success, "Test container exists and could not be removed for initialization via xPDOManager->removeSourceContainer()");
    }

    /**
     * Verify test create database works.
     *
     * @depends testRemoveSourceContainer
     */
    public function testCreateSourceContainer() {
        $created= $this->xpdo->getManager()->createSourceContainer();

        $this->assertTrue($created == true, "Could not create database.");
    }

    /**
     * Verify xPDO::connect works.
     */
    public function testConnect() {
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
        try {
            $this->xpdo->getManager();
            $result[] = $this->xpdo->manager->createObjectContainer('Person');
            $result[] = $this->xpdo->manager->createObjectContainer('Phone');
            $result[] = $this->xpdo->manager->createObjectContainer('PersonPhone');
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
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
        try {
            $this->xpdo->getManager();
            $driver= xPDOTestHarness::$properties['xpdo_driver'];
            $result= $this->xpdo->manager->createSourceContainer(
                    xPDOTestHarness::$properties[$driver . '_string_dsn_test'],
                    xPDOTestHarness::$properties[$driver . '_string_username'],
                    xPDOTestHarness::$properties[$driver . '_string_password']
            );
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == false, "Error testing overwriting source container with createSourceContainer() method");
    }

    /**
     * Tests xPDO::escape
     */
    public function testEscape() {
        $correct = 'test';
        $correct = trim($correct, $this->xpdo->_escapeCharOpen . $this->xpdo->_escapeCharClose);
        $correct = $this->xpdo->_escapeCharOpen . $correct . $this->xpdo->_escapeCharClose;

        $eco = $this->xpdo->_escapeCharOpen;
        $ecc = $this->xpdo->_escapeCharClose;
        $this->assertEquals($correct,$this->xpdo->escape('test'),'xpdo->escape() did not correctly escape.');
        $this->assertEquals($correct,$this->xpdo->escape($eco.'test'),'xpdo->escape() did not strip the beginning escape character before escaping.');
        $this->assertEquals($correct,$this->xpdo->escape($eco.'test'.$ecc),'xpdo->escape() did not strip the beginning and end escape character before escaping.');
        $this->assertEquals($correct,$this->xpdo->escape('test'.$ecc),'xpdo->escape() did not strip the end escape character before escaping.');
    }

    /**
     * Test xPDO::escSplit
     */
    public function testEscSplit() {        
        $str = '1,2,3';
        $result = xPDO::escSplit(',',$str,$this->xpdo->_escapeCharOpen);
        $this->assertTrue(is_array($result),'xPDO::escSplit did not return an array.');
        $this->assertEquals(3,count($result),'xPDO::escSplit did not return the correct number of indices.');
    }

    /**
     * Test xPDO::fromJSON
     */
    public function testFromJson() {
        $json = '{"key":"value","nested":{"foo":"123","another":"test"}}';
        $result = $this->xpdo->fromJSON($json);
        $this->assertTrue(is_array($result),'xpdo->fromJSON() did not return an array.');
    }

    /**
     * Test xPDO::toJSON
     */
    public function testToJson() {
        $array = array(
            'key' => 'value',
            'nested' => array(
                'foo' => '123',
                'another' => 'test',
            ),
        );
        $result = $this->xpdo->toJSON($array);
        $this->assertTrue(is_string($result),'xpdo->fromJSON() did not return an array.');
    }

    /**
     * Test xPDO::getManager
     */
    public function testGetManager() {
        $manager = $this->xpdo->getManager();
        $success = is_object($manager) && $manager instanceof xPDOManager;
        $this->assertTrue($success,'xpdo->getManager did not return an xPDOManager instance.');
    }

    /**
     * Test xPDO::getDriver
     */
    public function testGetDriver() {
        $driver = $this->xpdo->getDriver();
        $success = is_object($driver) && $driver instanceof xPDODriver;
        $this->assertTrue($success,'xpdo->getDriver did not return an xPDODriver instance.');
    }

    /**
     * Test xPDO::getCacheManager
     */
    public function testGetCacheManager() {
        $cacheManager = $this->xpdo->getCacheManager();
        $success = is_object($cacheManager) && $cacheManager instanceof xPDOCacheManager;
        $this->assertTrue($success,'xpdo->getCacheManager did not return an xPDOCacheManager instance.');
    }

    /**
     * Test xPDO::getCachePath
     */
    public function testGetCachePath() {
        $cachePath = $this->xpdo->getCachePath();
        $this->assertEquals($cachePath,XPDO_CORE_PATH.'cache/','xpdo->getCachePath() did not return the correct cache path.');
    }
    
    /**
     * Verify xPDO::newQuery returns a xPDOQuery object
     */
    public function testNewQuery() {
        $criteria = $this->xpdo->newQuery('Person');
        $success = is_object($criteria) && $criteria instanceof xPDOQuery;
        $this->assertTrue($success);
    }

    /**
     * Tests xPDO::getAncestry and make sure it returns an array of the correct
     * data.
     * 
     * @dataProvider providerGetAncestry
     */
    public function testGetAncestry($class,array $correct = array(),$includeSelf = true) {
        $anc = $this->xpdo->getAncestry($class,$includeSelf);
        $diff = array_diff($correct,$anc);
        $diff2 = array_diff($anc,$correct);
        $success = is_array($anc) && empty($diff) && empty($diff2);
        $this->assertTrue($success);
    }
    /**
     * Data provider for testGetAncestry
     */
    public function providerGetAncestry() {
        return array(
            array('Person',array('Person','xPDOSimpleObject','xPDOObject')),
            array('Person',array('xPDOSimpleObject','xPDOObject'),false),
        );
    }

    /**
     * Test xPDO->getSelectColumns.
     *
     * $className, $tableAlias= '', $columnPrefix= '', $columns= array (), $exclude= false
     */
    public function testGetSelectColumns() {
        $correct = '`id`, `first_name`, `last_name`, `middle_name`, `date_modified`, `dob`, `gender`, `blood_type`, `username`, `password`, `security_level`';
        $columns = $this->xpdo->getSelectColumns('Person');
        $this->assertEquals($columns,$correct);

        $correct = '`Person`.`id`, `Person`.`first_name`, `Person`.`last_name`, `Person`.`middle_name`, `Person`.`date_modified`, `Person`.`dob`, `Person`.`gender`, `Person`.`blood_type`, `Person`.`username`, `Person`.`password`, `Person`.`security_level`';
        $columns = $this->xpdo->getSelectColumns('Person','Person');
        $this->assertEquals($columns,$correct);

        $correct = '`Person`.`id` AS `test_id`, `Person`.`first_name` AS `test_first_name`, `Person`.`last_name` AS `test_last_name`, `Person`.`middle_name` AS `test_middle_name`, `Person`.`date_modified` AS `test_date_modified`, `Person`.`dob` AS `test_dob`, `Person`.`gender` AS `test_gender`, `Person`.`blood_type` AS `test_blood_type`, `Person`.`username` AS `test_username`, `Person`.`password` AS `test_password`, `Person`.`security_level` AS `test_security_level`';
        $columns = $this->xpdo->getSelectColumns('Person','Person','test_');
        $this->assertEquals($columns,$correct);

        $selectColumns = array('id','last_name','dob');
        $correct = '`id`, `last_name`, `dob`';
        $columns = $this->xpdo->getSelectColumns('Person','','',$selectColumns);
        $this->assertEquals($columns,$correct);
        
        $selectColumns = array('first_name','middle_name','dob','gender','security_level','blood_type');
        $correct = '`id`, `last_name`, `date_modified`, `username`, `password`';
        $columns = $this->xpdo->getSelectColumns('Person','','',$selectColumns,true);
        $this->assertEquals($columns,$correct);
    }
}
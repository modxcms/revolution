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
 * Tests related to xPDOObject methods using xPDOSample
 *
 * @package xpdo-test
 * @subpackage xpdo
 */
class xPDOSampleTest extends xPDOTestCase {
    /**
     * Setup dummy data for each test.
     */
    public function setUp() {
        parent::setUp();
        try {
            /* ensure we have clear data and identity sequences */
            $this->xpdo->getManager();

            $this->xpdo->manager->createObjectContainer('xPDOSample');

            for ($i = 10; $i > 0; $i--) {
                $xpdoSample = $this->xpdo->newObject('xPDOSample');
                $xpdoSample->fromArray(array(
                    'parent' => 0,
                    'unique_varchar' => uniqid('unique_varchar_'),
                    'varchar' => uniqid('varchar_', true),
                    'text' => uniqid('text', true),
                    'unix_timestamp' => time(),
                    'date_time' => strftime('%Y-%m-%d %H:%M:%S'),
                    'date' => strftime('%Y-%m-%d'),
                    'password' => uniqid('password_'),
                    'integer' => mt_rand(),
                    'float' => mt_rand() / mt_rand(),
                    'boolean' => mt_rand(0, 1) ? true : false,
                    'class_key' => mt_rand(0, 1) ? 'Foo' : 'Foobar'
                ));
                $xpdoSample->save();
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
    }

    /**
     * Remove dummy data prior to each test.
     */
    public function tearDown() {
        try {
            $this->xpdo->manager->removeObjectContainer('xPDOSample');
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        parent::tearDown();
    }

    /**
     * Test validating an object.
     *
     * @dataProvider providerValidate
     * @param $class
     * @param $data
     * @param $options
     * @param $expected
     */
    public function testValidate($class, $data, $options, $expected) {
        $validated = null;
        try {
            /** @var xPDOObject $object  */
            $object = $this->xpdo->newObject($class);
            $object->fromArray($data);
            $validated = $object->validate($options);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertEquals($expected, $validated, "Expected validation failed: " . print_r($object->_validator->getMessages(), true));
    }
    public function providerValidate() {
        return array(
            array(
                'xPDOSample',
                array(
                    'parent' => 0,
                    'unique_varchar' => uniqid('unique_varchar_'),
                    'varchar' => uniqid('varchar_', true),
                    'text' => uniqid('text', true),
                    'unix_timestamp' => time(),
                    'date_time' => strftime('%Y-%m-%d %H:%M:%S'),
                    'date' => strftime('%Y-%m-%d'),
                    'password' => uniqid('password_'),
                    'integer' => mt_rand(),
                    'float' => mt_rand() / mt_rand(),
                    'boolean' => mt_rand(0, 1) ? true : false,
                    'class_key' => mt_rand(0, 9999)
                ),
                array(),
                true
            ),
            array(
                'xPDOSample',
                array(
                ),
                array(),
                true
            ),
        );
    }

    /**
     * Test getting an object by the primary key.
     */
    public function testGetObjectByPK() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result= false;        
        try {
            $object= $this->xpdo->getObject('xPDOSample', 1);
            $result= (is_object($object) && $object->getPrimaryKey() == 1);
            if ($object) $this->xpdo->log(xPDO::LOG_LEVEL_INFO, "Object after retrieval: " . print_r($object->toArray(), true));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result, "Error retrieving object by primary key");
    }

    /**
     * Test xPDO::getCollection
     */
    public function testGetCollection() {        
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $collection= $this->xpdo->getCollection('xPDOSample');
            $this->xpdo->log(xPDO::LOG_LEVEL_INFO, "Collection total: " . count($collection));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(isset($collection[1]) && $collection[1] instanceof xPDOSample, "All objects do not match expected type.");
        $this->assertTrue(isset($collection[2]) && $collection[2] instanceof xPDOSample, "All objects do not match expected type.");
        $this->assertTrue(count($collection) == 10, "Error retrieving all objects.");
    }

    /**
     * Test loading an iterator for
     */
    public function testGetIterator() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $children = array();
        /** @var xPDOObject $object */
        try {
            $iterator = $this->xpdo->getIterator('xPDOSample');
            foreach ($iterator as $object) {
                $collection[] = $object;
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(
            count($collection) == 10
            && $collection[0] instanceof xPDOSample
            && $collection[1] instanceof xPDOSample,
            "Could not retrieve requested iterator."
        );
    }

    /**
     * Test removing an object
     */
    public function testRemoveObject() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result= false;

        try {
            if ($object= $this->xpdo->getObject('xPDOSample', 1)) {
                $result= $object->remove();
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    /**
     * Test removing a collection of objects
     */
    public function testRemoveCollection() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result= false;

        try {
            $result = $this->xpdo->removeCollection('xPDOSample', array('id:!=' => 1));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result === 9, "Error removing a collection of objects.");
    }
}

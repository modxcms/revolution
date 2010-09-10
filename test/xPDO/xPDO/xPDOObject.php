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
 * Tests related to basic xPDOObject methods
 *
 * @package xpdo-test
 * @subpackage xpdo
 */
class xPDOObjectTest extends xPDOTestCase {
    /**
     * Test saving an object.
     */
    public function testSaveObject() {
        $result= false;
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $person= $xpdo->newObject('Person');
            $person->set('first_name', 'Bob');
            $person->set('last_name', 'Bla');
            $person->set('middle_name', 'La');
            $person->set('dob', '1971-07-22');
            $person->set('password', 'b0bl4bl4');
            $person->set('username', 'boblabla');
            $person->set('security_level', 1);
            $person->set('gender', 'M');
            $result= $person->save();
            $xpdo->log(xPDO::LOG_LEVEL_INFO, "Object after save: " . print_r($person->toArray(), true));
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result, "Error saving data.");
    }

    /**
     * Tests a cascading save
     */
    public function testCascadeSave() {
        $xpdo = xPDOTestHarness::_getConnection();
        $result= false;
        try {
            $person= $xpdo->newObject('Person');
            $person->set('first_name', 'Bob');
            $person->set('last_name', 'Bla');
            $person->set('middle_name', 'Lu');
            $person->set('dob', '1971-07-21');
            $person->set('gender', 'M');
            $person->set('password', 'b0blubl4!');
            $person->set('username', 'boblubla');
            $person->set('security_level', 1);

            $phone1= $xpdo->newObject('Phone');
            $phone1->set('type', 'home');
            $phone1->set('number', '+1 555 555 5555');

            $phone2= $xpdo->newObject('Phone');
            $phone2->set('type', 'work');
            $phone2->set('number', '+1 555 555 4444');

            $personPhone1= $xpdo->newObject('PersonPhone');
            $personPhone1->addOne($phone1);
            $personPhone1->set('is_primary', false);

            $personPhone2= $xpdo->newObject('PersonPhone');
            $personPhone2->addOne($phone2);
            $personPhone2->set('is_primary', true);

            $personPhone= array($personPhone1, $personPhone2);

            $person->addMany($personPhone);

            $result= $person->save();
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error saving data.");
        $this->assertTrue(count($person->_relatedObjects['PersonPhone']) == 2, "Error saving related object data.");
    }

    /**
     * Test xPDOObject::getOne
     */
    public function testGetOne() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $personPhone= $xpdo->getObject('PersonPhone', array (
                2,
                1
            ));
            if ($personPhone) {
                $person= & $personPhone->getOne('Person');
                $phone= & $personPhone->getOne('Phone');
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving PersonPhone object by primary key");
        $this->assertTrue($person instanceof Person, "Error retrieving related Person object");
        $this->assertTrue($phone instanceof Phone, "Error retrieving related Phone object");
    }

    /**
     * Test xPDOObject::getMany
     */
    public function testGetMany() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $person= $xpdo->getObject('Person', 2);
            if ($person) {
                $collPersonPhone= $person->getMany('PersonPhone');
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($collPersonPhone) && $collPersonPhone['2-1'] instanceof PersonPhone, "Error retrieving related objects with getMany().");
        $this->assertTrue(!empty($collPersonPhone) && count($collPersonPhone) == 2, "Error retrieving proper objects with getMany().");
    }

    /**
     * Test getting an object by the primary key.
     *
     * @depends testSaveObject
     */
    public function testGetObjectByPK() {
        $result= false;
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $person= $xpdo->getObject('Person', 1);
            $result= (is_object($person) && $person->getPrimaryKey() == 1);
            if ($person) $xpdo->log(xPDO::LOG_LEVEL_INFO, "Object after retrieval: " . print_r($person->toArray(), true));
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result, "Error retrieving object by primary key");
    }

    /**
     * Test using getObject by PK on multiple objects, including multiple PKs
     */
    public function testGetObjectsByPK() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $person= $xpdo->getObject('Person', 2);
            $phone= $xpdo->getObject('Phone', 1);
            $personPhone= $xpdo->getObject('PersonPhone', array (
                2,
                1
            ));
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($person instanceof Person, "Error retrieving Person object by primary key");
        $this->assertTrue($phone instanceof Phone, "Error retrieving Phone object by primary key");
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving PersonPhone object by primary key");
    }

    /**
     * Test getObjectGraph by PK
     */
    public function testGetObjectGraphsByPK() {
        $xpdo = xPDOTestHarness::_getConnection();
        //array method
        try {
            $person= $xpdo->getObjectGraph('Person', array ('PersonPhone' => array ('Phone' => array ())), 2);
            if ($person) {
                $personPhoneColl= $person->getMany('PersonPhone');
                if ($personPhoneColl) {
                    $phone= null;
                    foreach ($personPhoneColl as $personPhone) {
                        if ($personPhone->get('phone') == 1) {
                            $phone= $personPhone->getOne('Phone');
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($person instanceof Person, "Error retrieving Person object by primary key via getObjectGraph");
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving retreiving related PersonPhone collection via getObjectGraph");
        $this->assertTrue($phone instanceof Phone, "Error retrieving related Phone object via getObjectGraph");
    }

    /**
     * Test getObjectGraph by PK with JSON graph
     */
    public function testGetObjectGraphsJSONByPK() {
        $xpdo = xPDOTestHarness::_getConnection();
        //JSON method
        try {
            $person= $xpdo->getObjectGraph('Person', '{"PersonPhone":{"Phone":{}}}', 2);
            if ($person) {
                $personPhoneColl= $person->getMany('PersonPhone');
                if ($personPhoneColl) {
                    $phone= null;
                    foreach ($personPhoneColl as $personPhone) {
                        if ($personPhone->get('phone') == 1) {
                            $phone= $personPhone->getOne('Phone');
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($person instanceof Person, "Error retrieving Person object by primary key via getObjectGraph, JSON graph");
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving retreiving related PersonPhone collection via getObjectGraph, JSON graph");
        $this->assertTrue($phone instanceof Phone, "Error retrieving related Phone object via getObjectGraph, JSON graph");
    }

    /**
     * Test xPDO::getCollection
     */
    public function testGetCollection() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $people= $xpdo->getCollection('Person');
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(isset($people[1]) && $people[1] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    /**
     * Test xPDO::getCollectionGraph
     */
    public function testGetCollectionGraph() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $people= $xpdo->getCollectionGraph('Person', array ('PersonPhone' => array ('Phone' => array ())));
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($people[1] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1'] instanceof PersonPhone, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1']->_relatedObjects['Phone'] instanceof Phone, "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    /**
     * Test xPDO::getCollectionGraph with JSON graph
     */
    public function testGetCollectionGraphJSON() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $people= $xpdo->getCollectionGraph('Person', '{"PersonPhone":{"Phone":{}}}');
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($people[1] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1'] instanceof PersonPhone, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1']->_relatedObjects['Phone'] instanceof Phone, "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }


    /**
     * Test removing an object
     */
    public function testRemoveObject() {
        $result= false;
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            if ($person= $xpdo->getObject('Person', 1)) {
                $result= $person->remove();
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    /**
     * Test removing a dependent object
     */
    public function testRemoveDependentObject() {
        $xpdo = xPDOTestHarness::_getConnection();
        $result= false;
        try {
            if ($phone= $xpdo->getObject('Phone', 1)) {
                $result= $phone->remove();
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    /**
     * Test removing circular composites
     */
    public function testRemoveCircularComposites() {
        $xpdo = xPDOTestHarness::_getConnection();
        $result= false;
        try {
            if ($personPhone= $xpdo->getObject('PersonPhone', array (2, 2))) {
                $result= $personPhone->remove();
                unset($personPhone);
                if ($result) {
                    if ($personPhone= $xpdo->getObject('PersonPhone', array (2, 2))) {
                        $this->assertTrue(false, "Parent object was not removed.");
                    }
                    if ($phone= $xpdo->getObject('Phone', 2)) {
                        $this->assertTrue(false, "Child object was not removed.");
                    }
                }
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error removing objects with circular composite relationships.");
    }
}
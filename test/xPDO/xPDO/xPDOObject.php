<?php
/**
 * Copyright 2010-2012 by MODX, LLC.
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
     * Setup dummy data for each test.
     */
    public function setUp() {
        parent::setUp();
        try {
            /* ensure we have clear data and identity sequences */
            $this->xpdo->getManager();

            $this->xpdo->manager->createObjectContainer('Phone');
            $this->xpdo->manager->createObjectContainer('Person');
            $this->xpdo->manager->createObjectContainer('PersonPhone');
            $this->xpdo->manager->createObjectContainer('BloodType');

            $bloodTypes = array('A+','A-','B+','B-','AB+','AB-','O+','O-');
            foreach ($bloodTypes as $bloodType) {
                $bt = $this->xpdo->newObject('BloodType');
                $bt->set('type',$bloodType);
                $bt->set('description','');
                if (!$bt->save()) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_FATAL,'Could not add blood type: '.$bloodType);
                }
            }

            $bloodTypeABPlus = $this->xpdo->getObject('BloodType','AB+');
            if (empty($bloodTypeABPlus)) $this->xpdo->log(xPDO::LOG_LEVEL_FATAL,'Could not load blood type.');

            /* add some people */
            $person= $this->xpdo->newObject('Person');
            $person->set('first_name', 'Johnathon');
            $person->set('last_name', 'Doe');
            $person->set('middle_name', 'Harry');
            $person->set('dob', '1950-03-14');
            $person->set('gender', 'M');
            $person->set('password', 'ohb0ybuddy');
            $person->set('username', 'john.doe@gmail.com');
            $person->set('security_level', 3);
            $person->set('blood_type',$bloodTypeABPlus->get('type'));
            $person->save();

            $phone = $this->xpdo->newObject('Phone');
            $phone->fromArray(array(
                'type' => 'work',
                'number' => '555-111-1111',
            ));
            $phone->save();

            $personPhone = $this->xpdo->newObject('PersonPhone');
            $personPhone->fromArray(array(
                'person' => 1,
                'phone' => 1,
                'is_primary' => true,
            ),'',true,true);
            $personPhone->save();

            $person= $this->xpdo->newObject('Person');
            $person->set('first_name', 'Jane');
            $person->set('last_name', 'Heartstead');
            $person->set('middle_name', 'Cecilia');
            $person->set('dob', '1978-10-23');
            $person->set('gender', 'F');
            $person->set('password', 'n0w4yimdoingthat');
            $person->set('username', 'jane.heartstead@yahoo.com');
            $person->set('security_level',1);
            $person->set('blood_type',$bloodTypeABPlus->get('type'));
            $person->save();

            $phone = $this->xpdo->newObject('Phone');
            $phone->fromArray(array(
                'type' => 'work',
                'number' => '555-222-2222',
            ));
            $phone->save();

            $personPhone = $this->xpdo->newObject('PersonPhone');
            $personPhone->fromArray(array(
                'person' => 2,
                'phone' => 2,
                'is_primary' => true,
            ),'',true,true);
            $personPhone->save();

            $phone = $this->xpdo->newObject('Phone');
            $phone->fromArray(array(
                'type' => 'home',
                'number' => '555-555-5555',
            ));
            $phone->save();

            $personPhone = $this->xpdo->newObject('PersonPhone');
            $personPhone->fromArray(array(
                'person' => 2,
                'phone' => 3,
                'is_primary' => false,
            ),'',true,true);
            $personPhone->save();
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
    }

    /**
     * Remove dummy data prior to each test.
     */
    public function tearDown() {
        try {
            $this->xpdo->manager->removeObjectContainer('Phone');
            $this->xpdo->manager->removeObjectContainer('Person');
            $this->xpdo->manager->removeObjectContainer('PersonPhone');
            $this->xpdo->manager->removeObjectContainer('BloodType');
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        parent::tearDown();
    }
    
    /**
     * Test saving an object.
     */
    public function testSaveObject() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result= false;
        try {
            $person= $this->xpdo->newObject('Person');
            $person->set('first_name', 'Bob');
            $person->set('last_name', 'Bla');
            $person->set('middle_name', 'La');
            $person->set('dob', '1971-07-22');
            $person->set('password', 'b0bl4bl4');
            $person->set('username', 'boblabla');
            $person->set('security_level', 1);
            $person->set('gender', 'M');
            $result= $person->save();
            $this->xpdo->log(xPDO::LOG_LEVEL_INFO, "Object after save: " . print_r($person->toArray(), true));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result, "Error saving data.");
        $person->remove();
    }

    /**
     * Tests a cascading save
     */
    public function testCascadeSave() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result= false;
        try {
            $person= $this->xpdo->newObject('Person');
            $person->set('first_name', 'Bob');
            $person->set('last_name', 'Bla');
            $person->set('middle_name', 'Lu');
            $person->set('dob', '1971-07-21');
            $person->set('gender', 'M');
            $person->set('password', 'b0blubl4!');
            $person->set('username', 'boblubla');
            $person->set('security_level', 1);

            $phone1= $this->xpdo->newObject('Phone');
            $phone1->set('type', 'home');
            $phone1->set('number', '+1 555 555 5555');

            $phone2= $this->xpdo->newObject('Phone');
            $phone2->set('type', 'work');
            $phone2->set('number', '+1 555 555 4444');

            $personPhone1= $this->xpdo->newObject('PersonPhone');
            $personPhone1->addOne($phone1);
            $personPhone1->set('is_primary', false);

            $personPhone2= $this->xpdo->newObject('PersonPhone');
            $personPhone2->addOne($phone2);
            $personPhone2->set('is_primary', true);

            $personPhone= array($personPhone1, $personPhone2);

            $person->addMany($personPhone);

            $result= $person->save();
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error saving data.");
        $this->assertTrue(count($person->_relatedObjects['PersonPhone']) == 2, "Error saving related object data.");
        $person->remove();
    }

    /**
     * Test getting an object by the primary key.
     *
     * @depends testSaveObject
     */
    public function testGetObjectByPK() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result= false;        
        try {
            $person= $this->xpdo->getObject('Person',1);
            $result= (is_object($person) && $person->getPrimaryKey() == 1);
            if ($person) $this->xpdo->log(xPDO::LOG_LEVEL_INFO, "Object after retrieval: " . print_r($person->toArray(), true));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result, "Error retrieving object by primary key");
    }

    /**
     * Test using getObject by PK on multiple objects, including multiple PKs
     */
    public function testGetObjectsByPK() {        
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $person= $this->xpdo->getObject('Person', 2);
            $phone= $this->xpdo->getObject('Phone', 2);
            $personPhone= $this->xpdo->getObject('PersonPhone', array (
                2,
                2,
            ));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($person instanceof Person, "Error retrieving Person object by primary key");
        $this->assertTrue($phone instanceof Phone, "Error retrieving Phone object by primary key");
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving PersonPhone object by primary key");
    }

    /**
     * Test getObjectGraph by PK
     */
    public function testGetObjectGraphsByPK() {        
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        //array method
        try {
            $person= $this->xpdo->getObjectGraph('Person', array ('PersonPhone' => array ('Phone' => array ())), 2);
            if ($person) {
                $personPhoneColl= $person->getMany('PersonPhone');
                if ($personPhoneColl) {
                    $phone= null;
                    foreach ($personPhoneColl as $personPhone) {
                        if ($personPhone->get('phone') == 2) {
                            $phone= $personPhone->getOne('Phone');
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($person instanceof Person, "Error retrieving Person object by primary key via getObjectGraph");
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving retreiving related PersonPhone collection via getObjectGraph");
        $this->assertTrue($phone instanceof Phone, "Error retrieving related Phone object via getObjectGraph");
    }

    /**
     * Test getObjectGraph by PK with JSON graph
     */
    public function testGetObjectGraphsJSONByPK() {        
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        //JSON method
        try {
            $person= $this->xpdo->getObjectGraph('Person', '{"PersonPhone":{"Phone":{}}}', 2);
            if ($person) {
                $personPhoneColl= $person->getMany('PersonPhone');
                if ($personPhoneColl) {
                    $phone= null;
                    foreach ($personPhoneColl as $personPhone) {
                        if ($personPhone->get('phone') == 2) {
                            $phone= $personPhone->getOne('Phone');
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($person instanceof Person, "Error retrieving Person object by primary key via getObjectGraph, JSON graph");
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving retreiving related PersonPhone collection via getObjectGraph, JSON graph");
        $this->assertTrue($phone instanceof Phone, "Error retrieving related Phone object via getObjectGraph, JSON graph");
    }

    /**
     * Test xPDO::getCollection
     */
    public function testGetCollection() {        
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $people= $this->xpdo->getCollection('Person');
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(isset($people[1]) && $people[1] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    /**
     * Test xPDO::getCollectionGraph
     */
    public function testGetCollectionGraph() {        
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $people= $this->xpdo->getCollectionGraph('Person', array ('PersonPhone' => array ('Phone' => array ())));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        return;
        $this->assertTrue($people[1] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1'] instanceof PersonPhone && $people[2]->_relatedObjects['PersonPhone']['2-2'] instanceof PersonPhone, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1']->_relatedObjects['Phone'] instanceof Phone && $people[2]->_relatedObjects['PersonPhone']['2-2']->_relatedObjects['Phone'] instanceof Phone, "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    /**
     * Test xPDO::getCollectionGraph with JSON graph
     */
    public function testGetCollectionGraphJSON() {        
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $people= $this->xpdo->getCollectionGraph('Person', '{"PersonPhone":{"Phone":{}}}');
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        return;
        $this->assertTrue($people[1] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1'] instanceof PersonPhone, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1']->_relatedObjects['Phone'] instanceof Phone, "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    /**
     * Test getMany
     * @dataProvider providerGetMany
     * @param string $person The username of the Person to use for the test data.
     * @param string $alias The relation alias to grab.
     * @param string $sortby A column to sort the related collection by.
     */
    public function testGetMany($person,$alias,$sortby) {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $person = $this->xpdo->getObject('Person',array(
            'username' => $person,
        ));
        if ($person) {
            try {
                $fkMeta = $person->getFKDefinition($alias);
                $personPhones = $person->getMany($alias, $this->xpdo->newQuery($fkMeta['class'])->sortby($this->xpdo->escape($sortby)));
            } catch (Exception $e) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
            }
        }
        $this->assertTrue(!empty($personPhones) && count($personPhones) === 2,'xPDOQuery: getMany failed from Person to PersonPhone.');
    }
    /**
     * Data provider for testGetMany
     */
    public function providerGetMany() {
        return array(
            array('jane.heartstead@yahoo.com','PersonPhone','is_primary'),
        );
    }


    /**
     * Test getOne
     * @dataProvider providerGetOne
     * @param string $username The username of the Person to use for the test data.
     * @param string $alias The relation alias to grab.
     */
    public function testGetOne($username,$alias,$class) {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $person = $this->xpdo->getObject('Person',array(
            'username' => $username,
        ));
        if ($person) {
            try {
                $one = $person->getOne($alias);
    
            } catch (Exception $e) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
            }
        }
        $this->assertTrue(!empty($one) && $one instanceof $class,'xPDOQuery: getMany failed from Person `'.$username.'` to '.$alias.'.');
    }
    /**
     * Data provider for testGetOne
     */
    public function providerGetOne() {
        return array(
            array('jane.heartstead@yahoo.com','BloodType','BloodType'),
        );
    }

    /**
     * Test loading a graph of relations to an xPDOObject instance.
     */
    public function testGetGraph() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        /** @var xPDOObject $object */
        $object = $this->xpdo->getObject('Person', 2);
        if ($object) {
            try {
                $object->getGraph(array('PersonPhone' => array('Phone' => array())));
            } catch (Exception $e) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
            }
        }
        $this->assertTrue(
            $object instanceof Person
            && $object->_relatedObjects['PersonPhone']['2-2'] instanceof PersonPhone
            && $object->_relatedObjects['PersonPhone']['2-2']->_relatedObjects['Phone'] instanceof Phone
            && $object->_relatedObjects['PersonPhone']['2-3'] instanceof PersonPhone
            && $object->_relatedObjects['PersonPhone']['2-3']->_relatedObjects['Phone'] instanceof Phone
            ,"Could not retrieve requested graph"
        );
    }

    /**
     * Test loading an iterator for
     */
    public function testGetIterator() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $children = array();
        /** @var xPDOObject $object */
        $object = $this->xpdo->getObject('Person', 2);
        if ($object) {
            try {
                $iterator = $object->getIterator('PersonPhone');
                foreach ($iterator as $child) {
                    $children[] = $child;
                }
            } catch (Exception $e) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
            }
        }
        $this->assertTrue(
            $object instanceof Person
            && $children[0] instanceof PersonPhone
            && $children[1] instanceof PersonPhone,
            "Could not retrieve requested iterator."
        );
    }

    /**
     * Test updating a collection.
     *
     * @dataProvider providerUpdateCollection
     * @param string $class The class to update a collection of.
     * @param array $set An array of field/value pairs to update for the collection.
     * @param mixed $criteria A valid xPDOCriteria object or expression.
     * @param array $expected An array of expected values for the test.
     */
    public function testUpdateCollection($class, $set, $criteria, $expected) {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $actualKeys = array_keys($set);
        $actualValues = array();
        $affected = $this->xpdo->updateCollection($class, $set, $criteria);
        $affectedCollection = $this->xpdo->getCollection($class, $criteria);
        foreach ($affectedCollection as $affectedObject) {
            $actualValues[] = $affectedObject->get($actualKeys);
        }
        $actual = array($affected, $actualValues);
        $this->assertEquals($expected, $actual, "Could not update collection as expected.");
    }
    /**
     * Data provider for testUpdateCollection
     */
    public function providerUpdateCollection() {
        return array(
            array('Person', array('dob' => '2011-08-09'), array('dob:<' => '1951-01-01'), array(1, array())),
            array('Person', array('security_level' => 5), array('security_level' => 3), array(1, array())),
            array('Person', array('date_of_birth' => '2011-09-01'), null, array(2, array(array('date_of_birth' => '2011-09-01'), array('date_of_birth' => '2011-09-01')))),
            array('Person', array('date_of_birth' => null), array('security_level' => 3), array(1, array(array('date_of_birth' => null)))),
        );
    }


    /**
     * Test removing an object
     */
    public function testRemoveObject() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result= false;

        $person = $this->xpdo->newObject('Person');
        $person->set('first_name', 'Kurt');
        $person->set('last_name', 'Dirt');
        $person->set('middle_name', 'Remover');
        $person->set('dob', '1978-10-23');
        $person->set('gender', 'F');
        $person->set('password', 'fdsfdsfdsfds');
        $person->set('username', 'dirt@remover.com');
        $person->set('security_level',1);
        $person->save();
        try {
            if ($person= $this->xpdo->getObject('Person', $person->get('id'))) {
                $result= $person->remove();
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    /**
     * Test removing a dependent object
     */
    public function testRemoveDependentObject() {        
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result= false;
        $phone = $this->xpdo->newObject('Phone');
        $phone->set('type', 'work');
        $phone->set('number', '555-789-4563');
        $phone->set('is_primary',false);
        $phone->save();
        try {
            if ($phone= $this->xpdo->getObject('Phone', $phone->get('id'))) {
                $result= $phone->remove();
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    /**
     * Test removing circular composites
     */
    public function testRemoveCircularComposites() {        
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result= false;
        try {
            if ($personPhone= $this->xpdo->getObject('PersonPhone', array (2, 2))) {
                $result= $personPhone->remove();
                unset($personPhone);
                if ($result) {
                    if ($personPhone= $this->xpdo->getObject('PersonPhone', array (2, 2))) {
                        $this->assertTrue(false, "Parent object was not removed.");
                    }
                    if ($phone= $this->xpdo->getObject('Phone', 2)) {
                        $this->assertTrue(false, "Child object was not removed.");
                    }
                }
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error removing objects with circular composite relationships.");
    }

    /**
     * Test removing a collection of objects
     */
    public function testRemoveCollection() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result= false;

        $person = $this->xpdo->newObject('Person');
        $person->set('first_name', 'Ready');
        $person->set('last_name', 'Willing');
        $person->set('middle_name', 'Able');
        $person->set('dob', '1980-12-25');
        $person->set('gender', 'M');
        $person->set('password', 'blahblahblah');
        $person->set('username', 'ready@willingandable.com');
        $person->set('security_level',1);
        $person->save();

        $person = $this->xpdo->newObject('Person');
        $person->set('first_name', 'Kurt');
        $person->set('last_name', 'Dirt');
        $person->set('middle_name', 'Remover');
        $person->set('dob', '1978-10-23');
        $person->set('gender', 'F');
        $person->set('password', 'fdsfdsfdsfds');
        $person->set('username', 'dirt@remover.com');
        $person->set('security_level',2);
        $person->save();

        unset($person);
        try {
            $result = $this->xpdo->removeCollection('Person', array('last_name:IN' => array('Willing', 'Dirt')));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result === 2, "Error removing a collection of objects.");
    }
}

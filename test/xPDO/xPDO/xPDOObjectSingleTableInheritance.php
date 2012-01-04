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
 * Tests related to basic xPDOObject methods when using single-table inheritance features
 *
 * @package xpdo-test
 * @subpackage xpdo
 */
class xPDOObjectSingleTableInheritanceTest extends xPDOTestCase {
    /**
     * Setup dummy data for each test.
     */
    public function setUp() {
        parent::setUp();
        try {
            /* ensure we have clear data and identity sequences */
            $this->xpdo->getManager();
            $this->xpdo->addPackage('sample.sti', xPDOTestHarness::$properties['xpdo_test_path'] . 'model/');

            $this->xpdo->manager->createObjectContainer('sti.baseClass');
            $this->xpdo->manager->createObjectContainer('sti.relClassOne');
            $this->xpdo->manager->createObjectContainer('sti.relClassMany');

            /* add some various base and derivative objects */
            $object= $this->xpdo->newObject('sti.baseClass');
            $object->set('field1', 1);
            $object->set('field2', 'a string');

            $relatedObject = $this->xpdo->newObject('sti.relClassOne');
            $relatedObject->fromArray(array(
                'field1' => 123,
                'field2' => 'alphanumeric',
            ));
            $object->addOne($relatedObject);

            $relatedObjects[0] = $this->xpdo->newObject('sti.relClassMany');
            $relatedObjects[0]->fromArray(array(
                'field1' => 'some text',
                'field2' => true,
            ));
            $relatedObjects[1] = $this->xpdo->newObject('sti.relClassMany');
            $relatedObjects[1]->fromArray(array(
                'field1' => 'some different text',
                'field2' => false,
            ));

            $object->addMany($relatedObjects);
            $object->save();

            /* add some various base and derivative objects */
            $object= $this->xpdo->newObject('sti.derivedClass');
            $object->set('field1', 2);
            $object->set('field2', 'a derived string');

            $relatedObject = $this->xpdo->newObject('sti.relClassOne');
            $relatedObject->fromArray(array(
                'field1' => 321,
                'field2' => 'numericalpha',
            ));
            $object->addOne($relatedObject);

            $relatedObjects[0] = $this->xpdo->newObject('sti.relClassMany');
            $relatedObjects[0]->fromArray(array(
                'field1' => 'some derived text',
                'field2' => true,
            ));
            $relatedObjects[1] = $this->xpdo->newObject('sti.relClassMany');
            $relatedObjects[1]->fromArray(array(
                'field1' => 'some different derived text',
                'field2' => false,
            ));

            $object->addMany($relatedObjects);
            $object->save();

            /* add some various base and derivative objects */
            $object= $this->xpdo->newObject('sti.derivedClass2');
            $object->set('field1', 3);
            $object->set('field2', 'another derived string');

            $relatedObject = $this->xpdo->newObject('sti.relClassOne');
            $relatedObject->fromArray(array(
                'field1' => 789,
                'field2' => 'derived numericalpha',
            ));
            $object->addOne($relatedObject, 'relOne');

            $relatedObjects[0] = $this->xpdo->newObject('sti.relClassMany');
            $relatedObjects[0]->fromArray(array(
                'field1' => 'some double derived text',
                'field2' => true,
            ));
            $relatedObjects[1] = $this->xpdo->newObject('sti.relClassMany');
            $relatedObjects[1]->fromArray(array(
                'field1' => 'some different double derived text',
                'field2' => false,
            ));

            $object->addMany($relatedObjects, 'relMany');
            $object->save();

        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
    }

    /**
     * Remove dummy data prior to each test.
     */
    public function tearDown() {
        try {
            $this->xpdo->manager->removeObjectContainer('sti.baseClass');
            $this->xpdo->manager->removeObjectContainer('sti.relClassOne');
			$this->xpdo->manager->removeObjectContainer('sti.relClassMany');
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        parent::tearDown();
    }

    /**
     * Test getting the proper derived instance from getObject.
     */
    public function testGetDerivedObject() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $baseObject = $this->xpdo->getObject('sti.baseClass', array('field1' => 1));
            $derivedObject = $this->xpdo->getObject('sti.baseClass', array('field1' => 2));
            $derivedObject2 = $this->xpdo->getObject('sti.baseClass', array('field1' => 3));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($baseObject instanceof baseClass && $baseObject->get('class_key') == 'baseClass', "Error getting base object of the appropriate class.");
        $this->assertTrue($derivedObject instanceof derivedClass && $derivedObject->get('class_key') == 'derivedClass', "Error getting derived object of the appropriate class.");
        $this->assertTrue($derivedObject2 instanceof derivedClass2 && $derivedObject2->get('class_key') == 'derivedClass2', "Error getting derived object of the appropriate class.");
    }

    /**
     * Test getting only the requested derived instance from getObject.
     * @dataProvider providerGetSpecificDerivedObject
     * @param string $expectedClass A valid xPDO table class name derived from a base table class.
     */
    public function testGetSpecificDerivedObject($expectedClass, $criteria) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result = false;
        $realClass = '(none)';
        try {
            $object = $this->xpdo->getObject("sti.{$expectedClass}", $criteria);
            if ($object) {
                $result = ($object instanceof $expectedClass && $object->get('class_key') == $expectedClass);
                if ($result !== true) $realClass = $object->_class;
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result === true, "Error getting a derived class instance for {$expectedClass}; got {$realClass}.");
    }
    /**
     * Data provider for testGetSpecificDerivedObject.
     */
    public function providerGetSpecificDerivedObject() {
        return array(
            array('derivedClass', array('field1' => 2)),
            array('derivedClass2', array('field1' => 3))
        );
    }

    /**
     * Test getting the proper derived instances from getCollection.
     */
    public function testGetDerivedCollection() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $collection = $this->xpdo->getCollection('sti.baseClass');
            foreach ($collection as $object) {
                $result = false;
                switch ($object->get('field1')) {
                    case 1:
                        $expectedClass = 'baseClass';
                        $result = ($object instanceof baseClass && $object->get('class_key') == 'baseClass');
                        break;
                    case 2:
                        $expectedClass = 'derivedClass';
                        $result = ($object instanceof derivedClass && $object->get('class_key') == 'derivedClass');
                        break;
                    case 3:
                        $expectedClass = 'derivedClass2';
                        $result = ($object instanceof derivedClass2 && $object->get('class_key') == 'derivedClass2');
                        break;
                }
                $this->assertTrue($result, "Error getting derived object of the appropriate class ({$expectedClass}) in collection.");
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
    }

    /**
     * Test getting only the requested derived instances from getCollection.
     * @dataProvider providerGetSpecificDerivedCollection
     * @param string $expectedClass A valid xPDO table class name derived from a base table class.
     */
    public function testGetSpecificDerivedCollection($expectedClass) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $collection = $this->xpdo->getCollection("sti.{$expectedClass}");
            foreach ($collection as $object) {
                $result = ($object instanceof $expectedClass && $object->get('class_key') == $expectedClass);
                $this->assertTrue($result, "Error only getting derived objects of the specified derived class in collection.");
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
    }
    /**
     * Data provider for testGetSpecificDerivedCollection.
     */
    public function providerGetSpecificDerivedCollection() {
        return array(
            array('derivedClass'),
            array('derivedClass2')
        );
    }

    /**
     * Test getting the proper derived instance from getObjectGraph.
     */
    public function testGetDerivedObjectGraph() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $baseObject = $this->xpdo->getObjectGraph('sti.baseClass', '{"relOne":{},"relMany":{}}', array('field1' => 1));
            $derivedObject = $this->xpdo->getObjectGraph('sti.baseClass', '{"relOne":{},"relMany":{}}', array('field1' => 2));
            $derivedObject2 = $this->xpdo->getObjectGraph('sti.baseClass', '{"relOne":{},"relMany":{}}', array('field1' => 3));
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($baseObject instanceof baseClass && $baseObject->get('class_key') == 'baseClass', "Error getting base object of the appropriate class from graph.");
        $this->assertTrue($derivedObject instanceof derivedClass && $derivedObject->get('class_key') == 'derivedClass', "Error getting derived object of the appropriate class from graph.");
        $this->assertTrue($derivedObject2 instanceof derivedClass2 && $derivedObject2->get('class_key') == 'derivedClass2', "Error getting derived object of the appropriate class from graph.");
    }

    /**
     * Test getting the proper derived instance from getCollection.
     */
    public function testGetDerivedCollectionGraph() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $collection = $this->xpdo->getCollectionGraph('sti.baseClass', '{"relOne":{},"relMany":{}}');
            foreach ($collection as $object) {
                $result = false;
                switch ($object->get('field1')) {
                    case 1:
                        $expectedClass = 'baseClass';
                        $result = ($object instanceof baseClass && $object->get('class_key') == 'baseClass');
                        break;
                    case 2:
                        $expectedClass = 'derivedClass';
                        $result = ($object instanceof derivedClass && $object->get('class_key') == 'derivedClass');
                        break;
                    case 3:
                        $expectedClass = 'derivedClass2';
                        $result = ($object instanceof derivedClass2 && $object->get('class_key') == 'derivedClass2');
                        break;
                }
                $this->assertTrue($result, "Error getting derived object of the appropriate class ({$expectedClass}) in collection graph.");
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
    }

    /**
     * Test saving instances of a base and various derived classes.
     *
     * @param $expectedClass The expected class of the instance.
     * @param $criteria The criteria for selecting the instance.
     * @param $update The changes to make to the instance data to test the save.
     * @dataProvider providerSaveDerivedObject
     */
    public function testSaveDerivedObject($expectedClass, $criteria, $update) {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $result = false;
        try {
            $object = $this->xpdo->getObject("sti.baseClass", $criteria);
            if ($object) {
                while (list($key, $value) = each($update)) {
                    $object->set($key, $value);
                }
                $result = $object->save();
            }
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result === true, "Error saving class instance for expected class {$expectedClass}.");
    }
    /**
     * Data provider for testSaveDerivedObject.
     */
    public function providerSaveDerivedObject() {
        return array(
            array('baseClass', array('field1' => 1), array('field2' => 'updated base class string')),
            array('derivedClass', array('field1' => 2), array('field2' => 'updated derived class string')),
            array('derivedClass2', array('field1' => 3), array('field2' => 'updated derived class 2 string', 'field3' => 'updated derived field content'))
        );
    }
}
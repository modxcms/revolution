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
 * Tests related to the xPDOQuery class.
 *
 * @package xpdo-test
 * @subpackage xpdoquery
 */
class xPDOQueryTest extends xPDOTestCase {
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
                $bt->save();
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
                'number' => '555-123-4567',
            ));
            $phone->save();

            $personPhone = $this->xpdo->newObject('PersonPhone');
            $personPhone->fromArray(array(
                'person' => $person->get('id'),
                'phone' => $phone->get('id'),
                'is_primary' => true,
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
     * Test xPDOQuery->where() statements
     */
    public function testWhere() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $where = array(
            'first_name' => 'Johnathon',
            'last_name' => 'Doe',
        );

        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where($where,xPDOQuery::SQL_AND,null,0);
            $person = $this->xpdo->getObject('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }

        /* test to see if criteria was added */
        $this->assertTrue(is_array($criteria->query['where']),'xpdoquery->where(): Criteria did not result in an array.');

        /* are these necessary to test? */
        $this->assertTrue(!empty($criteria->query['where'][0]),'xpdoquery->where(): Criteria was not added.');
        $conditions = $criteria->query['where'][0][0];
        $this->assertTrue(is_object($conditions[0]) && $conditions[0] instanceof xPDOQueryCondition,'xPDOQuery->where(): Condition is not an xPDOQueryCondition type.');

        /* test for results */
        $this->assertTrue(is_object($person) && $person instanceof Person,'xPDOQuery->where(): Query did not return correct results.');
    }

    /**
     * Test = xPDOQuery condition
     * @dataProvider providerEquals
     */
    public function testEquals($a) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:=' => $a,
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: = Clause does not find the correct result.');
    }
    /**
     * Test condition provider for testEquals
     */
    public function providerEquals() {
        return array(
            array(1,3),
        );
    }

    /**
     * Test != xPDOQuery condition
     * @dataProvider providerNotEquals
     */
    public function testNotEquals($a) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:!=' => $a,
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: != Clause does not find the correct result.');
    }
    /**
     * Test condition provider for testEquals
     */
    public function providerNotEquals() {
        return array(
            array(1,3,999,-1,'aaa'),
        );
    }

    /**
     * Test > xPDOQuery condition
     * @dataProvider providerGreaterThan
     */
    public function testGreaterThan($a) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        /* test > */
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:>' => $a,
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: > Clause does not find the correct result.');
    }
    /**
     * Test condition provider for testGreaterThan
     */
    public function providerGreaterThan() {
        return array(
            array(2,0,-1),
        );
    }

    /**
     * Test >= xPDOQuery condition
     * @dataProvider providerGreaterThanEquals
     */
    public function testGreaterThanEquals($a) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:>=' => $a,
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: >= Clause does not find the correct result.');
    }
    /**
     * Test condition provider for testGreaterThanEquals
     */
    public function providerGreaterThanEquals() {
        return array(
            array(3,0,-1),
        );
    }

    /**
     * Test < xPDOQuery condition
     * @dataProvider providerLessThan
     */
    public function testLessThan($a) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:<' => $a,
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: < Clause does not find the correct result.');
    }
    /**
     * Test condition provider for testLessThan
     */
    public function providerLessThan() {
        return array(
            array(4,999),
        );
    }

    /**
     * Test <= xPDOQuery condition
     * @dataProvider providerLessThanEquals
     */
    public function testLessThanEquals() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:<=' => 3,
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: <= Clause does not find the correct result.');
    }
    /**
     * Test condition provider for testLessThan
     */
    public function providerLessThanEquals() {
        return array(
            array(4,999),
        );
    }

    /**
     * Test <> xPDOQuery condition
     * @dataProvider providerNotGTLT
     */
    public function testNotGTLT() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:<>' => 999,
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: <> Clause does not find the correct result.');
    }
    /**
     * Test condition provider for testLessThan
     */
    public function providerNotGTLT() {
        return array(
            array(4,999,'abc'),
        );
    }
    
    /**
     * Test LIKE xPDOQuery conditions
     */
    public function testLike() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        /* test LIKE %.. */
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'first_name:LIKE' => '%nathon',
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: LIKE %.. Clause does not find the correct result.');
        
        /* test LIKE ..% */
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'first_name:LIKE' => 'John%',
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: LIKE ..% Clause does not find the correct result.');

        /* test LIKE %..% */
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'first_name:LIKE' => '%Johna%',
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: LIKE %..% Clause does not find the correct result.');
    }

    /**
     * Test IN xPDOQuery condition
     */
    public function testIn() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        /* test IN with strings */
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'first_name:IN' => array('Johnathon','Mary'),
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result) && count($result) === 1,'xPDOQuery: IN with strings Clause does not find the correct result.');

        /* test IN with ints */
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:IN' => array(1,3),
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result) && count($result) === 2,'xPDOQuery: IN with INTs Clause does not find the correct result.');

        /* test IN with () condition */
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level IN (1,3)',
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result) && count($result) === 2,'xPDOQuery: IN with () condition does not find the correct result.');
    }

    /**
     * Test NOT IN xPDOQuery condition
     */
    public function testNotIn() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        /* test NOT IN with strings */
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'first_name:NOT IN' => array('Johnathon','Mary'),
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result) && count($result) === 1,'xPDOQuery: NOT IN with strings Clause does not find the correct result.');

        /* test IN with ints */
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:NOT IN' => array(2,3),
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result) && count($result) === 1,'xPDOQuery: NOT IN with INTs Clause does not find the correct result.');

        /* test IN with () condition */
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level NOT IN (2,3)',
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result) && count($result) === 1,'xPDOQuery: NOT IN with () condition does not find the correct result.');
    }

    /**
     * Test nested array conditions
     * @dataProvider providerNestedConditions
     */
    public function testNestedConditions($level,$lastName,$gender) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:>' => $level,
                array(
                    'OR:last_name:LIKE' => $lastName,
                    'gender:=' => $gender,
                ),
            ));
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: Nested condition clause does not find the correct result.');
    }
    /**
     * Data provider for testNestedConditions
     */
    public function providerNestedConditions() {
        return array(
            array(4,'%Do%','M'),
            array(3,'%Do%','M'),
            array(4,'%oe%','M'),
        );
    }

    /**
     * Test sortby
     * @dataProvider providerSortBy
     * @param string $sortBy The column to sort by
     * @param string $sortDir The direction to sort
     * @param string $resultColumn The column to check the expected value of the first result.
     * @param mixed $resultValue The expected value of the first result.
     */
    public function testSortBy($sortBy,$sortDir,$resultColumn,$resultValue) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->sortby($sortBy,$sortDir);
            $people = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }

        foreach ($people as $person) {
            $result = $person;
            break;
        }

        $success = $result->get($resultColumn) == $resultValue;
        $this->assertTrue($success,'xPDOQuery: Sortby clause returned incorrect result.');
    }
    /**
     * Data provider for testSortBy
     */
    public function providerSortBy() {
        return array(
            array('first_name','ASC','first_name','Jane'),
            array('last_name','DESC','first_name','Jane'),
            array('first_name','DESC','last_name','Doe'),
        );
    }

    /**
     * Test limit
     * @dataProvider providerLimit
     * @param int $limit A number to limit by
     * @param boolean $shouldEqual If the result count should equal the limit
     */
    public function testLimit($limit,$shouldEqual) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $criteria = $this->xpdo->newQuery('Person');
            $criteria->limit($limit);
            $result = $this->xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $success = count($result) == $limit;
        if (!$shouldEqual) $success = !$success;
        $this->assertTrue($success,'xPDOQuery: Limit clause returned more than desired '.$limit.' result.');
    }
    /**
     * Data provider for testLimit
     */
    public function providerLimit() {
        return array(
            array(1,true),
            array(2,true),
            array(5,false),
        );
    }
}
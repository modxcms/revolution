<?php
/**
 * Tests related to the xPDOQuery class.
 *
 * @package xpdo-test
 * @subpackage xpdoquery
 */
class xPDOQueryTest extends xPDOTestCase {
    /**
     * Setup dummy data for tests.
     */
    public function setUp() {
        $xpdo = xPDOTestHarness::_getConnection();
        $person= $xpdo->newObject('Person');
        $person->set('first_name', 'Johnathon');
        $person->set('last_name', 'Doe');
        $person->set('middle_name', 'Harry');
        $person->set('dob', '1950-03-14');
        $person->set('gender', 'M');
        $person->set('password', 'ohb0ybuddy');
        $person->set('username', 'john.doe@gmail.com');
        $person->set('security_level', 3);
        $person->save();
    }

    /**
     * Remove dummy data prior to each test.
     */
    public function tearDown() {
        $xpdo = xPDOTestHarness::_getConnection();
        $person = $xpdo->getObject('Person',array(
            'username' => 'john.doe@gmail.com'
        ));
        $person->remove();
    }
    
    /**
     * Test xPDOQuery->where() statements
     */
    public function testWhere() {
        $xpdo = xPDOTestHarness::_getConnection();

        $where = array(
            'first_name' => 'Johnathon',
            'last_name' => 'Doe',
        );

        $criteria = $xpdo->newQuery('Person');
        $criteria->where($where,xPDOQuery::SQL_AND,null,0);

        /* test to see if criteria was added */
        $this->assertTrue(is_array($criteria->query['where']),'xpdoquery->where(): Criteria did not result in an array.');

        /* are these necessary to test? */
        $this->assertTrue(!empty($criteria->query['where'][0]),'xpdoquery->where(): Criteria was not added.');
        $conditions = $criteria->query['where'][0][0];
        $this->assertTrue(is_object($conditions[0]) && $conditions[0] instanceof xPDOQueryCondition,'xPDOQuery->where(): Condition is not an xPDOQueryCondition type.');

        /* test for results */
        $person = $xpdo->getObject('Person',$criteria);
        $this->assertTrue(is_object($person) && $person instanceof Person,'xPDOQuery->where(): Query did not return correct results.');
    }

    /**
     * Test > xPDOQuery condition
     */
    public function testGreaterThan() {
        $xpdo = xPDOTestHarness::_getConnection();

        /* test > */
        $criteria = $xpdo->newQuery('Person');
        $criteria->where(array(
            'security_level:>' => 2,
        ));
        $result = $xpdo->getCollection('Person',$criteria);
        $this->assertTrue(!empty($result),'xPDOQuery: > Clause does not find the correct result.');
    }

    /**
     * Test >= xPDOQuery condition
     */
    public function testGreaterThanEquals() {
        $xpdo = xPDOTestHarness::_getConnection();
        $criteria = $xpdo->newQuery('Person');
        $criteria->where(array(
            'security_level:>=' => 3,
        ));
        $result = $xpdo->getCollection('Person',$criteria);
        $this->assertTrue(!empty($result),'xPDOQuery: >= Clause does not find the correct result.');
    }

    /**
     * Test < xPDOQuery condition
     */
    public function testLessThan() {
        $xpdo = xPDOTestHarness::_getConnection();
        $criteria = $xpdo->newQuery('Person');
        $criteria->where(array(
            'security_level:<' => 4,
        ));
        $result = $xpdo->getCollection('Person',$criteria);
        $this->assertTrue(!empty($result),'xPDOQuery: < Clause does not find the correct result.');
    }

    /**
     * Test <= xPDOQuery condition
     */
    public function testLessThanEquals() {
        $xpdo = xPDOTestHarness::_getConnection();
        $criteria = $xpdo->newQuery('Person');
        $criteria->where(array(
            'security_level:<=' => 3,
        ));
        $result = $xpdo->getCollection('Person',$criteria);
        $this->assertTrue(!empty($result),'xPDOQuery: <= Clause does not find the correct result.');
    }

    /**
     * Test <> xPDOQuery condition
     */
    public function testNotGTLT() {
        $xpdo = xPDOTestHarness::_getConnection();
        $criteria = $xpdo->newQuery('Person');
        $criteria->where(array(
            'security_level:<>' => 999,
        ));
        $result = $xpdo->getCollection('Person',$criteria);
        $this->assertTrue(!empty($result),'xPDOQuery: <> Clause does not find the correct result.');
    }
    
    /**
     * Test LIKE xPDOQuery conditions
     */
    public function testLike() {
        $xpdo = xPDOTestHarness::_getConnection();

        /* test LIKE %.. */
        $criteria = $xpdo->newQuery('Person');
        $criteria->where(array(
            'first_name:LIKE' => '%nathon',
        ));
        $result = $xpdo->getCollection('Person',$criteria);
        $this->assertTrue(!empty($result),'xPDOQuery: LIKE %.. Clause does not find the correct result.');
        
        /* test LIKE ..% */
        $criteria = $xpdo->newQuery('Person');
        $criteria->where(array(
            'first_name:LIKE' => 'John%',
        ));
        $result = $xpdo->getCollection('Person',$criteria);
        $this->assertTrue(!empty($result),'xPDOQuery: LIKE ..% Clause does not find the correct result.');

        /* test LIKE %..% */
        $criteria = $xpdo->newQuery('Person');
        $criteria->where(array(
            'first_name:LIKE' => '%Johna%',
        ));
        $result = $xpdo->getCollection('Person',$criteria);
        $this->assertTrue(!empty($result),'xPDOQuery: LIKE %..% Clause does not find the correct result.');

    }

    /**
     * Test IN xPDOQuery condition
     */
    public function testIn() {
        $xpdo = xPDOTestHarness::_getConnection();
        
        /* test IN with strings */
        $criteria = $xpdo->newQuery('Person');
        $criteria->where(array(
            'first_name:IN' => array('Johnathon','Mary'),
        ));
        $result = $xpdo->getCollection('Person',$criteria);
        $this->assertTrue(!empty($result),'xPDOQuery: IN with strings Clause does not find the correct result.');

        /* test IN with ints */
        $criteria = $xpdo->newQuery('Person');
        $criteria->where(array(
            'security_level:IN' => array(1,3),
        ));
        $result = $xpdo->getCollection('Person',$criteria);
        $this->assertTrue(!empty($result),'xPDOQuery: IN with INTs Clause does not find the correct result.');
    }
}
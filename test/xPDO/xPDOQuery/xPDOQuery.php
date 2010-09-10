<?php
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
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            /* ensure we have clear data */
            $xpdo->removeCollection('Phone',array());
            $xpdo->removeCollection('Person',array());
            $xpdo->removeCollection('PersonPhone',array());

            /* add some people */
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

            $person= $xpdo->newObject('Person');
            $person->set('first_name', 'Jane');
            $person->set('last_name', 'Heartstead');
            $person->set('middle_name', 'Cecilia');
            $person->set('dob', '1978-10-23');
            $person->set('gender', 'F');
            $person->set('password', 'n0w4yimdoingthat');
            $person->set('username', 'jane.heartstead@yahoo.com');
            $person->set('security_level',1);
            $person->save();
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
    }

    /**
     * Remove dummy data prior to each test.
     */
    public function tearDown() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $person = $xpdo->getObject('Person',array(
                'username' => 'john.doe@gmail.com'
            ));
            $person->remove();
            $person = $xpdo->getObject('Person',array(
                'username' => 'jane.heartstead@yahoo.com'
            ));
            $person->remove();
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
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

        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where($where,xPDOQuery::SQL_AND,null,0);
            $person = $xpdo->getObject('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
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
     */
    public function testEquals() {
        $xpdo = xPDOTestHarness::_getConnection();

        /* test > */
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:=' => 3,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: = Clause does not find the correct result.');
    }

    /**
     * Test != xPDOQuery condition
     */
    public function testNotEquals() {
        $xpdo = xPDOTestHarness::_getConnection();

        /* test > */
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:!=' => 1,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: != Clause does not find the correct result.');
    }

    /**
     * Test > xPDOQuery condition
     */
    public function testGreaterThan() {
        $xpdo = xPDOTestHarness::_getConnection();

        /* test > */
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:>' => 2,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: > Clause does not find the correct result.');
    }

    /**
     * Test >= xPDOQuery condition
     */
    public function testGreaterThanEquals() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:>=' => 3,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: >= Clause does not find the correct result.');
    }

    /**
     * Test < xPDOQuery condition
     */
    public function testLessThan() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:<' => 4,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: < Clause does not find the correct result.');
    }

    /**
     * Test <= xPDOQuery condition
     */
    public function testLessThanEquals() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:<=' => 3,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: <= Clause does not find the correct result.');
    }

    /**
     * Test <> xPDOQuery condition
     */
    public function testNotGTLT() {
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:<>' => 999,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: <> Clause does not find the correct result.');
    }
    
    /**
     * Test LIKE xPDOQuery conditions
     */
    public function testLike() {
        $xpdo = xPDOTestHarness::_getConnection();

        /* test LIKE %.. */
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'first_name:LIKE' => '%nathon',
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: LIKE %.. Clause does not find the correct result.');
        
        /* test LIKE ..% */
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'first_name:LIKE' => 'John%',
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: LIKE ..% Clause does not find the correct result.');

        /* test LIKE %..% */
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'first_name:LIKE' => '%Johna%',
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: LIKE %..% Clause does not find the correct result.');

    }

    /**
     * Test IN xPDOQuery condition
     */
    public function testIn() {
        $xpdo = xPDOTestHarness::_getConnection();
        
        /* test IN with strings */
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'first_name:IN' => array('Johnathon','Mary'),
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: IN with strings Clause does not find the correct result.');

        /* test IN with ints */
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:IN' => array(1,3),
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: IN with INTs Clause does not find the correct result.');

        /* test IN with () condition */
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level IN (1,3)',
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: IN with () condition does not find the correct result.');
    }

    /**
     * Test nested array conditions
     */
    public function testNestedConditions() {
        $xpdo = xPDOTestHarness::_getConnection();

        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:>' => 4,
                array(
                    'OR:last_name:LIKE' => '%Do%',
                    'gender:=' => 'M',
                ),
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($result),'xPDOQuery: Nested condition clause does not find the correct result.');
    }

    /**
     * Test sortby
     */
    public function testSortBy() {
        $xpdo = xPDOTestHarness::_getConnection();

        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->sortby('first_name','ASC');
            $people = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }

        foreach ($people as $person) {
            $result = $person;
            break;
        }

        $success = $result->get('first_name') == 'Jane';
        $this->assertTrue($success,'xPDOQuery: Sortby clause returned incorrect result.');
    }

    /**
     * Test limit
     */
    public function testLimit() {
        $xpdo = xPDOTestHarness::_getConnection();

        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->limit(1);
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(count($result) == 1,'xPDOQuery: Limit clause returned more than desired 1 result.');
    }
}
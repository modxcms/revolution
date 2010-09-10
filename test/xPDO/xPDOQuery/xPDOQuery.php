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
     * @dataProvider providerEquals
     */
    public function testEquals($a) {
        $xpdo = xPDOTestHarness::_getConnection();

        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:=' => $a,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
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
        $xpdo = xPDOTestHarness::_getConnection();

        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:!=' => $a,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
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
        $xpdo = xPDOTestHarness::_getConnection();

        /* test > */
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:>' => $a,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
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
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:>=' => $a,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
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
        $xpdo = xPDOTestHarness::_getConnection();
        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:<' => $a,
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
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
     * @dataProvider providerNestedConditions
     */
    public function testNestedConditions($level,$lastName,$gender) {
        $xpdo = xPDOTestHarness::_getConnection();

        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->where(array(
                'security_level:>' => $level,
                array(
                    'OR:last_name:LIKE' => $lastName,
                    'gender:=' => $gender,
                ),
            ));
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
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
        $xpdo = xPDOTestHarness::_getConnection();

        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->sortby($sortBy,$sortDir);
            $people = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
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
        $xpdo = xPDOTestHarness::_getConnection();

        try {
            $criteria = $xpdo->newQuery('Person');
            $criteria->limit($limit);
            $result = $xpdo->getCollection('Person',$criteria);
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
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
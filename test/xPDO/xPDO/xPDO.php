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
        $this->assertTrue($success, "Test container exists and could not be removed for initialization.");
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
        $this->assertTrue($connect);
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
     * Test saving an object.
     * 
     * @depends testCreateObjectContainer
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
}
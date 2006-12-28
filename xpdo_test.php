<?php
require_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../../simpletest/unit_tester.php');
require_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../../simpletest/reporter.php');
require_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../xpdo/xpdo.class.php');

class TestOfXPDO extends UnitTestCase {
    var $xpdo= null;
    var $properties= array ();

    function TestOfXPDO() {
    }

    function setUp() {
        $properties= array ();
        include (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/properties.inc.php');
        $this->properties= $properties;
    }

    function getXPDOObject() {
        $xpdo= new xPDO($this->properties['xpdo_string_dsn_test'], $this->properties['xpdo_string_username'], $this->properties['xpdo_string_password']);
        if (is_object($xpdo)) {
            $xpdo->setAttribute(PDO_ATTR_ERRMODE, PDO_ERRMODE_SILENT);
            $xpdo->setPackage('sample');
            $xpdo->setDebug(false); // set to true for debugging during tests only
        }
        return $xpdo;
    }

    function testConnectionError() {
        $string_dsn= 'mysql:host= nosuchhost;dbname=nosuchdb';
        $mypdo= new xPDO($string_dsn, "nonesuchuser", "nonesuchpass");
        $mypdo->setAttribute(PDO_ATTR_ERRMODE, PDO_ERRMODE_SILENT);
        $mypdo->setDebug(false);
        // Should be an error set since we gave bogus info
        $this->assertTrue(strlen($mypdo->errorCode()) > 0 || substr($mypdo->errorCode, 3) == '000', "Error code not being set on PDO object");
    }

    function testInitialize() {
        if ($xpdo= $this->getXPDOObject()) {
            $manager= & $xpdo->getManager();
            $removed= $manager->removeSourceContainer();
        } else {
            $removed= true;
        }
        $this->assertTrue($removed == true, "Test container exists and could not be removed for initialization");
    }

    function testCreateDatabase() {
        $config= xPDO :: parseDSN($this->properties['xpdo_string_dsn_test']);
        include_once (strtr(realpath(dirname(__FILE__)) . '/../xpdo/om/' . $config['dbtype'] . '/xpdomanager.class.php', '\\', '/'));
        $created= xPDOManager :: createSourceContainer($this->properties['xpdo_string_dsn_test'], $this->properties['xpdo_string_username'], $this->properties['xpdo_string_password']);
        $this->assertTrue($created == true, "Could not create database");
    }

    function testConnection() {
        $xpdo= $this->getXPDOObject();
        // Should be no errors if we connected successfully to the database that was created
        $this->assertTrue(is_object($xpdo), "Could not connect to database");
    }

    function testSaveObject() {
        $result= false;
        $xpdo= $this->getXPDOObject();
        $person= $xpdo->newObject('Person');
        $person->set('first_name', 'Bob');
        $person->set('last_name', 'Bla');
        $person->set('middle_name', 'La');
//        $person->set('date_modified', date('Y-m-d H:i:s'));
        $person->set('dob', '1971-07-22');
        $person->set('gender', 'M');
        $person->set('blood_type', 'AB-');
        $person->set('password', 'b0bl4bl4');
        $person->set('username', 'boblabla');
        $person->set('security_level', 1);
        $result= $person->save();
        $xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Object after save: " . print_r($person, true));
        $this->assertTrue($result, "Error saving data.");
    }
    
    function testSaveSampleObject() {
        $result= false;
        $xpdo= $this->getXPDOObject();
//        $xpdo->setDebug(true);
        $sample= $xpdo->newObject('xPDOSample');
        $sample->set('parent', null);
        $sample->set('unique_varchar', '');
        $sample->set('varchar', '');
        $sample->set('text', null);
        $sample->set('timestamp', null);
        $sample->set('unix_timestamp', time(), 'integer');
        $sample->set('date_time', null);
        $sample->set('date', null);
        $sample->set('enum', '');
        $sample->set('password', 'password');
        $sample->set('integer', 1);
        $sample->set('boolean', false);
        $result= $sample->save();
        $xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Object after save: " . print_r($sample, true));
//        $xpdo->setDebug(false);
        $this->assertTrue($result, "Error saving xPDOSample object data.");
    }

    function testGetObjectByPK() {
        $result= false;
        $xpdo= $this->getXPDOObject();
//        $xpdo->setDebug(true);
        if ($person= $xpdo->getObject('Person', 1)) {
            $result= ($person->getPrimaryKey() == 1);
        }
        $xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Object after retrieval: " . print_r($person, true));
//        $xpdo->setDebug(false);
        $this->assertTrue($result, "Error retrieving object by primary key");
    }

    function testAddOne() {
        $xpdo= $this->getXPDOObject();
        $result= false;

        $person= $xpdo->newObject('Person');
        $phone= $xpdo->newObject('Phone');

        $person->set('first_name', 'Bob');
        $person->set('last_name', 'Bla');
        $person->set('middle_name', 'La');
//        $person->set('date_modified', date('Y-m-d H:i:s'));
        $person->set('dob', '1971-07-22');
        $person->set('gender', 'M');
        $person->set('blood_type', 'AB-');
        $person->set('password', 'b0bl4bl4');
        $person->set('username', 'boblubla');
        $person->set('security_level', 1);
//        $person->save();

        $phone->set('type', 'home');
        $phone->set('number', '+1 555 555 5555');
        $phone->set('date_modified', date('Y-m-d H:i:s'));
        $phone->save();

//        $xpdo->setDebug(true);

        $personPhone= $xpdo->newObject('PersonPhone');
        $personPhone->addOne($phone);
        $personPhone->set('is_primary', false);

//        $xpdo->setDebug(false);
        
        $person->addMany($personPhone);
        
        $result= $person->save();
//        $result= ($personPhone->get('person') == $person->get('id') && $personPhone->get('phone') == $phone->get('id'));
        $this->assertTrue($result == true, "Error saving data.");
    }

    function testGetObjectsByPK() {
        $xpdo= $this->getXPDOObject();
        $person= $xpdo->getObject('Person', 2);
        $phone= $xpdo->getObject('Phone', 1);
        $personPhone= $xpdo->getObject('PersonPhone', array (
            2,
            1
        ));
        $this->assertTrue(is_a($person, 'Person'), "Error retrieving Person object by primary key");
        $this->assertTrue(is_a($phone, 'Phone'), "Error retrieving Phone object by primary key");
        $this->assertTrue(is_a($personPhone, 'PersonPhone'), "Error retrieving PersonPhone object by primary key");
    }

    function testRemoveObject() {
        $xpdo= $this->getXPDOObject();
        $result= false;
        if ($person= $xpdo->getObject('Person', 1)) {
            $result= $person->remove();
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    function testDoNotOverwriteSourceContainer() {
        // by default, if the connection fails, it should just error out
        // Should be an error set since we gave bogus info
        $config= xPDO :: parseDSN($this->properties['xpdo_string_dsn_test']);
        include_once (strtr(realpath(dirname(__FILE__)) . '/../xpdo/om/' . $config['dbtype'] . '/xpdomanager.class.php', '\\', '/'));
        $created= xPDOManager :: createSourceContainer($this->properties['xpdo_string_dsn_test'], $this->properties['xpdo_string_username'], $this->properties['xpdo_string_password']);
        $this->assertTrue($created == false, "Attempted to create database even though it already exists");
    }

    function testRemoveSourceContainer() {
        return true;
        $removed= false;
        if ($xpdo= $this->getXPDOObject()) {
            $manager= & $xpdo->getManager();
            $removed= $manager->removeSourceContainer();
        }        
        $this->assertTrue($removed == true, "Error removing data source container");
    }
}

$test= & new TestOfXPDO();
$test->run(new TextReporter());
?>
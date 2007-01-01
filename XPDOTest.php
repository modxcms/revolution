<?php
require_once 'PHPUnit2/Framework/TestCase.php';

class XPDOTest extends PHPUnit2_Framework_TestCase {
    protected $xpdo= null;
    protected $properties= array ();

    protected function setUp() {
        $properties= array ();
        include (strtr(realpath(dirname(__FILE__)) . '/properties.inc.php', '\\', '/'));
        include_once (strtr(realpath(dirname(__FILE__)) . '/../xpdo/xpdo.class.php', '\\', '/'));
        $this->properties= $properties;
    }

    protected function getXPDOObject() {
        $xpdo= new xPDO($this->properties['xpdo_string_dsn_test'], $this->properties['xpdo_string_username'], $this->properties['xpdo_string_password']);
        if (is_object($xpdo)) {
            $xpdo->setAttribute(PDO_ATTR_ERRMODE, PDO_ERRMODE_SILENT);
            $xpdo->setPackage('sample');
            $xpdo->setDebug(false); // set to true for debugging during tests only
//            $xpdo->setLogTarget('HTML'); // set to 'HTML' for running through browser
        }
        return $xpdo;
    }

    public function testConnectionError() {
        $string_dsn= 'mysql:host= nosuchhost;dbname=nosuchdb';
        $mypdo= new xPDO($string_dsn, "nonesuchuser", "nonesuchpass");
        // Should be an error set since we gave bogus info
        $this->assertTrue(strlen($mypdo->errorCode()) > 0 || substr($mypdo->errorCode, 3) == '000', "Error code not being set on PDO object");
    }

    public function testInitialize() {
        $xpdo= $this->getXPDOObject();
        if ($xpdo) {
            $manager= & $xpdo->getManager();
            if ($manager->removeSourceContainer()) {
                $xpdo= null;
            }
        }
        $this->assertTrue($xpdo == null, "Test container exists and could not be removed for initialization");
    }

    public function testCreateDatabase() {
        $config= xPDO :: parseDSN($this->properties['xpdo_string_dsn_test']);
        include_once (strtr(realpath(dirname(__FILE__)) . '/../xpdo/om/' . $config['dbtype'] . '/xpdomanager.class.php', '\\', '/'));
        $created= xPDOManager :: createSourceContainer($this->properties['xpdo_string_dsn_test'], $this->properties['xpdo_string_username'], $this->properties['xpdo_string_password']);
        $this->assertTrue($created == true, "Could not create database");
        //      $this->assertTrue(strlen($xpdo->errorCode()) == 0, "Could not create database");
    }

    public function testConnection() {
        $xpdo= $this->getXPDOObject();
        // Should be no errors if we connected successfully to the database that was created
        $this->assertTrue(is_object($xpdo), "Could not connect to database");
    }

    public function testSaveObject() {
        $result= false;
        $xpdo= $this->getXPDOObject();
//        $xpdo->setDebug(true);
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
        $xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Object after save: " . print_r($person->_fields['date_modified'], true));
//        $xpdo->setDebug(false);
        $this->assertTrue($result, "Error saving data.");
//        exit();
    }

    public function testGetObjectByPK() {
        $result= false;
        $xpdo= $this->getXPDOObject();
        $person= $xpdo->getObject('Person', '1');
        $result= ($person->getPrimaryKey() === 1);
        $xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Object after retrieval: " . print_r($person, true));
        $this->assertTrue($result, "Error retrieving object by primary key");
    }

    public function testCascadeSave() {
        $xpdo= $this->getXPDOObject();
        $result= false;
        $person= $xpdo->newObject('Person');
        $person->set('first_name', 'Bob');
        $person->set('last_name', 'Bla');
        $person->set('middle_name', 'Lu');
//        $person->set('date_modified', date('Y-m-d H:i:s'));
        $person->set('dob', '1971-07-21');
        $person->set('gender', 'M');
        $person->set('blood_type', 'O+');
        $person->set('password', 'b0blubl4!');
        $person->set('username', 'boblubla');
        $person->set('security_level', 1);
//        $person->save();

        $phone= $xpdo->newObject('Phone');
        $phone->set('type', 'home');
        $phone->set('number', '+1 555 555 5555');
//        $phone->set('date_modified', date('Y-m-d H:i:s'));
        $phone->save();

//        $xpdo->setDebug(true);
        $personPhone= $xpdo->newObject('PersonPhone');
//        $personPhone->addOne($person);
        $personPhone->addOne($phone);
        $personPhone->set('is_primary', false);

        $xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Person\n" . print_r($person, true));
        
        $person->addMany($personPhone);
//        $result= $personPhone->save();
        $result= $person->save();
//        $xpdo->setDebug(false);
        $this->assertTrue($result == true, "Error saving data.");
    }
    
    public function testDeepCascadeSave() {
        //TODO: implement this
    }

    public function testGetObjectsByPK() {
        $xpdo= $this->getXPDOObject();
        $person= $xpdo->getObject('Person', '2');
        $phone= $xpdo->getObject('Phone', '1');
        $personPhone= $xpdo->getObject('PersonPhone', array (
            2,
            1
        ));
        $this->assertTrue($person instanceof Person, "Error retrieving Person object by primary key");
        $this->assertTrue($phone instanceof Phone, "Error retrieving Phone object by primary key");
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving PersonPhone object by primary key");
    }
    
    public function testGetOne() {
        $xpdo= $this->getXPDOObject();
//        $xpdo->setDebug(true);
        $personPhone= $xpdo->getObject('PersonPhone', array (
            2,
            1
        ));
        $person= & $personPhone->getOne('Person');
        $phone= & $personPhone->getOne('Phone');
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving PersonPhone object by primary key");
        $this->assertTrue($person instanceof Person, "Error retrieving related Person object");
        $this->assertTrue($phone instanceof Phone, "Error retrieving related Phone object");
    }
    
    public function testGetAll() {
        $xpdo= $this->getXPDOObject();
        $people= $xpdo->getCollection('Person');
        $this->assertTrue($people['1'] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue($people['2'] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    public function testGetMany() {
        $xpdo= $this->getXPDOObject();
//        $xpdo->setDebug(true);
        $person= $xpdo->getObject('Person', 2);
        $collPersonPhone= $person->getMany('PersonPhone');
//        $xpdo->_log(XPDO_LOG_LEVEL_DEBUG, print_r($collPersonPhone, true));
        $this->assertTrue($collPersonPhone['2-1'] instanceof PersonPhone, "Error retrieving related objects with getMany().");
        $this->assertTrue(count($collPersonPhone) == 1, "Error retrieving proper objects with getMany().");
    }

    public function testRemoveObject() {
        $result= false;
        $xpdo= $this->getXPDOObject();
        if ($person= $xpdo->getObject('Person', '1')) {
            $result= $person->remove();
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    public function testRemoveDependentObject() {
        $xpdo= $this->getXPDOObject();
        $result= false;
        if ($phone= $xpdo->getObject('Phone', '1')) {
            $result= $phone->remove();
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    public function testDoNotOverwriteSourceContainer() {
        // by default, if the connection fails, it should just error out
        // Should be an error set since we gave bogus info
        $xpdo= $this->getXPDOObject();
        $manager= & $xpdo->getManager();
        $result= $manager->createSourceContainer($this->properties['xpdo_string_dsn_test'], $this->properties['xpdo_string_username'], $this->properties['xpdo_string_password']);
        $this->assertTrue($result == false, "Error testing overwriting source container with createSourceContainer() method");
    }

    public function testRemoveSourceContainer() {
        return;
        $xpdo= $this->getXPDOObject();
        $manager= & $xpdo->getManager();
        $result= $manager->removeSourceContainer();
        $this->assertTrue($result == true, "Error code not being set on PDO object");
    }
}

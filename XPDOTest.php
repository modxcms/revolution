<?php
require_once 'PHPUnit/Framework/TestCase.php';

//uncomment to force emulated XPDO_MODE even if PDO exists
//define ('XPDO_MODE', 2);

class XPDOTest extends PHPUnit_Framework_TestCase {
    protected $xpdo= null;
    protected $properties= array ();

    protected function setUp() {
        $properties= array ();
        include (strtr(realpath(dirname(__FILE__)) . '/properties.inc.php', '\\', '/'));
        include_once (strtr(realpath(dirname(dirname(__FILE__))) . '/xpdo/xpdo.class.php', '\\', '/'));
        $this->properties= $properties;
    }

    protected function getXPDOObject() {
        $xpdo= new xPDO($this->properties['xpdo_string_dsn_test'], $this->properties['xpdo_string_username'], $this->properties['xpdo_string_password']);
        if (is_object($xpdo)) {
            $xpdo->setAttribute(PDO_ATTR_ERRMODE, PDO_ERRMODE_SILENT);
            $xpdo->setPackage('sample', strtr(realpath(dirname(dirname(__FILE__))) . '/model/', '\\', '/'));
//            $xpdo->setDebug(false); // set to true for debugging during tests only
            $xpdo->setLogLevel(XPDO_LOG_LEVEL_WARN); // set to 'HTML' for running through browser
            $xpdo->setLogTarget('ECHO'); // set to 'HTML' for running through browser
        }
        return $xpdo;
    }

    public function testConnectionError() {
        $string_dsn= 'mysql:host= nosuchhost;dbname=nosuchdb';
        $mypdo= new xPDO($string_dsn, "nonesuchuser", "nonesuchpass");
        $result= $mypdo->connect();
        // Should be an error set since we gave bogus info
        $this->assertTrue($result == false, "Connection was successful with bogus information.");
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
        if ($config['dbtype'] === 'sqlite2') $config['dbtype']= 'sqlite';
        include_once (strtr(realpath(dirname(__FILE__)) . '/../xpdo/om/' . $config['dbtype'] . '/xpdomanager.class.php', '\\', '/'));
        $created= xPDOManager :: createSourceContainer($this->properties['xpdo_string_dsn_test'], $this->properties['xpdo_string_username'], $this->properties['xpdo_string_password']);
        $this->assertTrue($created == true, "Could not create database");
        //      $this->assertTrue(strlen($xpdo->errorCode()) == 0, "Could not create database");
    }

    public function testConnection() {
        $xpdo= $this->getXPDOObject();
        $result= $xpdo->connect();
        // Should be no errors if we connected successfully to the database that was created
        $this->assertTrue(is_object($xpdo), "Could not get an xPDO instance");
        $this->assertTrue($result == true, "Could not connect to database");
    }

    public function testSaveObject() {
        $result= false;
        $xpdo= $this->getXPDOObject();
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
        $xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Object after save: " . print_r($person->toArray(), true));
        $this->assertTrue($result, "Error saving data.");
    }

    public function testGetObjectByPK() {
        $result= false;
        $xpdo= $this->getXPDOObject();
        $person= $xpdo->getObject('Person', 1);
        $result= (is_object($person) && $person->getPrimaryKey() == 1);
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
        $this->assertTrue($result == true, "Error saving data.");
        $this->assertTrue(count($person->_relatedObjects['PersonPhone']) == 2, "Error saving related object data.");
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

    public function testGetObjectGraphsByPK() {
        $xpdo= $this->getXPDOObject();
        //JSON method
        $person= $xpdo->getObjectGraph('Person', array ('PersonPhone' => array ('Phone' => array ())), 2);
        $personPhoneColl= $person->getMany('PersonPhone');
        $phone= null;
        foreach ($personPhoneColl as $personPhone) {
            if ($personPhone->get('phone') == 1) {
                $phone= $personPhone->getOne('Phone');
                break;
            }
        }
        $this->assertTrue(is_a($person, 'Person'), "Error retrieving Person object by primary key via getObjectGraph");
        $this->assertTrue(is_a($personPhone, 'PersonPhone'), "Error retrieving retreiving related PersonPhone collection via getObjectGraph");
        $this->assertTrue(is_a($phone, 'Phone'), "Error retrieving related Phone object via getObjectGraph");
    }

    public function testGetObjectGraphsJSONByPK() {
        $xpdo= $this->getXPDOObject();
        //JSON method
        $person= $xpdo->getObjectGraph('Person', '{"PersonPhone":{"Phone":{}}}', 2);
        $personPhoneColl= $person->getMany('PersonPhone');
        $phone= null;
        foreach ($personPhoneColl as $personPhone) {
            if ($personPhone->get('phone') == 1) {
                $phone= $personPhone->getOne('Phone');
                break;
            }
        }
        $this->assertTrue(is_a($person, 'Person'), "Error retrieving Person object by primary key via getObjectGraph, JSON graph");
        $this->assertTrue(is_a($personPhone, 'PersonPhone'), "Error retrieving retreiving related PersonPhone collection via getObjectGraph, JSON graph");
        $this->assertTrue(is_a($phone, 'Phone'), "Error retrieving related Phone object via getObjectGraph, JSON graph");
    }

    public function testGetOne() {
        $xpdo= $this->getXPDOObject();
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

    public function testGetCollectionGraph() {
        $xpdo= $this->getXPDOObject();
        $people= $xpdo->getCollectionGraph('Person', array ('PersonPhone' => array ('Phone' => array ())));

        $this->assertTrue(is_a($people['1'], 'Person'), "Error retrieving all objects.");
        $this->assertTrue(is_a($people['2'], 'Person'), "Error retrieving all objects.");
        $this->assertTrue(is_a($people['2']->_relatedObjects['PersonPhone']['2-1'], 'PersonPhone'), "Error retrieving all objects.");
        $this->assertTrue(is_a($people['2']->_relatedObjects['PersonPhone']['2-1']->_relatedObjects['Phone'], 'Phone'), "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    public function testGetCollectionGraphJSON() {
        $xpdo= $this->getXPDOObject();
        $people= $xpdo->getCollectionGraph('Person', '{"PersonPhone":{"Phone":{}}}');

        $this->assertTrue(is_a($people['1'], 'Person'), "Error retrieving all objects.");
        $this->assertTrue(is_a($people['2'], 'Person'), "Error retrieving all objects.");
        $this->assertTrue(is_a($people['2']->_relatedObjects['PersonPhone']['2-1'], 'PersonPhone'), "Error retrieving all objects.");
        $this->assertTrue(is_a($people['2']->_relatedObjects['PersonPhone']['2-1']->_relatedObjects['Phone'], 'Phone'), "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    public function testGetMany() {
        $xpdo= $this->getXPDOObject();
        $person= $xpdo->getObject('Person', 2);
        $collPersonPhone= $person->getMany('PersonPhone');
        $this->assertTrue($collPersonPhone['2-1'] instanceof PersonPhone, "Error retrieving related objects with getMany().");
        $this->assertTrue(count($collPersonPhone) == 2, "Error retrieving proper objects with getMany().");
    }

    public function testRemoveObject() {
//        return true;
        $result= false;
        $xpdo= $this->getXPDOObject();
        if ($person= $xpdo->getObject('Person', '1')) {
            $result= $person->remove();
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    public function testRemoveDependentObject() {
//        return true;
        $xpdo= $this->getXPDOObject();
        $result= false;
        if ($phone= $xpdo->getObject('Phone', '1')) {
            $result= $phone->remove();
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    public function testRemoveCircularComposites() {
//        return true;
        $xpdo= $this->getXPDOObject();
        $result= false;
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
        $this->assertTrue($result == true, "Error removing objects with circular composite relationships.");
    }

    public function testDoNotOverwriteSourceContainer() {
//        return true;
        // by default, if the connection fails, it should just error out
        // Should be an error set since we gave bogus info
        $xpdo= $this->getXPDOObject();
        $manager= & $xpdo->getManager();
        $result= $manager->createSourceContainer($this->properties['xpdo_string_dsn_test'], $this->properties['xpdo_string_username'], $this->properties['xpdo_string_password']);
        $this->assertTrue($result == false, "Error testing overwriting source container with createSourceContainer() method");
    }

    public function testRemoveSourceContainer() {
//        return true;
        $xpdo= $this->getXPDOObject();
        $manager= & $xpdo->getManager();
        $result= $manager->removeSourceContainer();
        $this->assertTrue($result == true, "Error code not being set on PDO object");
    }
}

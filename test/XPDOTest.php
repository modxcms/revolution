<?php
require_once 'PHPUnit/Framework/TestCase.php';
error_reporting(-1);

class XPDOTest extends PHPUnit_Framework_TestCase {
    protected $xpdo= null;
    protected $properties= array ();

    protected function setUp() {
        $properties= array ();
        include_once (strtr(realpath(dirname(dirname(__FILE__))) . '/xpdo/xpdo.class.php', '\\', '/'));
        include (strtr(realpath(dirname(__FILE__)) . '/properties.inc.php', '\\', '/'));
        $this->properties= $properties;
    }

    protected function getXPDOObject($options = array()) {
        $driver= $this->properties['xpdo_driver'];
        $dsn= $driver . '_' . (array_key_exists('dsnProperty', $options) ? $options['dsnProperty'] : 'string_dsn_test');
        $xpdo= new xPDO(
                $this->properties[$dsn],
                $this->properties["{$driver}_string_username"],
                $this->properties["{$driver}_string_password"],
                $this->properties["{$driver}_array_options"],
                $this->properties["{$driver}_array_driverOptions"]
        );
        if (is_object($xpdo)) {
            if ($dsn == $driver . '_string_dsn_test') {
                $xpdo->setPackage('sample', strtr(realpath(dirname(__FILE__)) . '/model/', '\\', '/'));
            }
            if (array_key_exists('debug', $this->properties)) {
                $xpdo->setDebug($this->properties['debug']); // set to true for debugging during tests only
            }
            $logLevel = array_key_exists('logLevel', $this->properties) ? $this->properties['logLevel'] : xPDO::LOG_LEVEL_WARN;
            $logTarget = array_key_exists('logTarget', $this->properties) ? $this->properties['logTarget'] : 'ECHO';
            $xpdo->setLogLevel($logLevel);
            $xpdo->setLogTarget($logTarget); // set to 'HTML' for running through browser
        }
        return $xpdo;
    }

    public function testConnectionError() {
        $string_dsn= $this->properties[$this->properties['xpdo_driver'] . '_string_dsn_error'];
        $mypdo= new xPDO($string_dsn, "nonesuchuser", "nonesuchpass");
        $result= $mypdo->connect();
        // Should be an error set since we gave bogus info
        $this->assertTrue($result == false, "Connection was successful with bogus information.");
    }

    public function testInitialize() {
        $xpdo= $this->getXPDOObject();
        if ($xpdo && $xpdo->connect()) {
            $response = $xpdo->getManager()->removeSourceContainer(xPDO::parseDSN($this->properties[$this->properties['xpdo_driver'] . '_string_dsn_test']));
            if ($response) {
                $xpdo= null;
            }
        } else {
            $xpdo = null;
        }
        $this->assertTrue($xpdo == null, "Test container exists and could not be removed for initialization");
    }

    public function testCreateDatabase() {
        $driver= $this->properties['xpdo_driver'];
//        $xpdo= $this->getXPDOObject($driver . '_string_dsn_nodb');
//        $created= $xpdo->getManager()->createSourceContainer(xPDO::parseDSN($this->properties[$driver . '_string_dsn_test']), $this->properties[$driver . '_string_username'], $this->properties[$driver . '_string_password']);
        $xpdo= $this->getXPDOObject();
        $created= $xpdo->getManager()->createSourceContainer();
        $this->assertTrue($created == true, "Could not create database");
        //      $this->assertTrue(strlen($xpdo->errorCode()) == 0, "Could not create database");
    }

    public function testConnection() {
        $xpdo= $this->getXPDOObject();
        $result= $xpdo->connect(array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        // Should be no errors if we connected successfully to the database that was created
        $this->assertTrue(is_object($xpdo), "Could not get an xPDO instance");
        $this->assertTrue($result == true, "Could not connect to database");
        $this->assertTrue($xpdo->getAttribute(PDO::ATTR_ERRMODE) == PDO::ERRMODE_WARNING, "Could not set PDO::ATTR_ERRMODE");
    }

    public function testCreateObjectContainers() {
        $xpdo= $this->getXPDOObject();
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

    public function testSaveObject() {
        $result= false;
        $xpdo= $this->getXPDOObject();
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

    public function testGetObjectByPK() {
        $result= false;
        $xpdo= $this->getXPDOObject();
        try {
            $person= $xpdo->getObject('Person', 1);
            $result= (is_object($person) && $person->getPrimaryKey() == 1);
            if ($person) $xpdo->log(xPDO::LOG_LEVEL_INFO, "Object after retrieval: " . print_r($person->toArray(), true));
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result, "Error retrieving object by primary key");
    }

    public function testCascadeSave() {
        $xpdo= $this->getXPDOObject();
        $result= false;
        try {
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
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error saving data.");
        $this->assertTrue(count($person->_relatedObjects['PersonPhone']) == 2, "Error saving related object data.");
    }

    public function testDeepCascadeSave() {
        //TODO: implement this
    }

    public function testGetObjectsByPK() {
        $xpdo= $this->getXPDOObject();
        try {
            $person= $xpdo->getObject('Person', 2);
            $phone= $xpdo->getObject('Phone', 1);
            $personPhone= $xpdo->getObject('PersonPhone', array (
                2,
                1
            ));
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($person instanceof Person, "Error retrieving Person object by primary key");
        $this->assertTrue($phone instanceof Phone, "Error retrieving Phone object by primary key");
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving PersonPhone object by primary key");
    }

    public function testGetObjectGraphsByPK() {
        $xpdo= $this->getXPDOObject();
        //array method
        try {
            $person= $xpdo->getObjectGraph('Person', array ('PersonPhone' => array ('Phone' => array ())), 2);
            if ($person) {
                $personPhoneColl= $person->getMany('PersonPhone');
                if ($personPhoneColl) {
                    $phone= null;
                    foreach ($personPhoneColl as $personPhone) {
                        if ($personPhone->get('phone') == 1) {
                            $phone= $personPhone->getOne('Phone');
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($person instanceof Person, "Error retrieving Person object by primary key via getObjectGraph");
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving retreiving related PersonPhone collection via getObjectGraph");
        $this->assertTrue($phone instanceof Phone, "Error retrieving related Phone object via getObjectGraph");
    }

    public function testGetObjectGraphsJSONByPK() {
        $xpdo= $this->getXPDOObject();
        //JSON method
        try {
            $person= $xpdo->getObjectGraph('Person', '{"PersonPhone":{"Phone":{}}}', 2);
            if ($person) {
                $personPhoneColl= $person->getMany('PersonPhone');
                if ($personPhoneColl) {
                    $phone= null;
                    foreach ($personPhoneColl as $personPhone) {
                        if ($personPhone->get('phone') == 1) {
                            $phone= $personPhone->getOne('Phone');
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($person instanceof Person, "Error retrieving Person object by primary key via getObjectGraph, JSON graph");
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving retreiving related PersonPhone collection via getObjectGraph, JSON graph");
        $this->assertTrue($phone instanceof Phone, "Error retrieving related Phone object via getObjectGraph, JSON graph");
    }

    public function testGetOne() {
        $xpdo= $this->getXPDOObject();
        try {
            $personPhone= $xpdo->getObject('PersonPhone', array (
                2,
                1
            ));
            if ($personPhone) {
                $person= & $personPhone->getOne('Person');
                $phone= & $personPhone->getOne('Phone');
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($personPhone instanceof PersonPhone, "Error retrieving PersonPhone object by primary key");
        $this->assertTrue($person instanceof Person, "Error retrieving related Person object");
        $this->assertTrue($phone instanceof Phone, "Error retrieving related Phone object");
    }

    public function testGetAll() {
        $xpdo= $this->getXPDOObject();
        try {
            $people= $xpdo->getCollection('Person');
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(isset($people[1]) && $people[1] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    public function testGetCollectionGraph() {
        $xpdo= $this->getXPDOObject();
        try {
            $people= $xpdo->getCollectionGraph('Person', array ('PersonPhone' => array ('Phone' => array ())));
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($people[1] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1'] instanceof PersonPhone, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1']->_relatedObjects['Phone'] instanceof Phone, "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    public function testGetCollectionGraphJSON() {
        $xpdo= $this->getXPDOObject();
        try {
            $people= $xpdo->getCollectionGraph('Person', '{"PersonPhone":{"Phone":{}}}');
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($people[1] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2] instanceof Person, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1'] instanceof PersonPhone, "Error retrieving all objects.");
        $this->assertTrue(isset($people[2]) && $people[2]->_relatedObjects['PersonPhone']['2-1']->_relatedObjects['Phone'] instanceof Phone, "Error retrieving all objects.");
        $this->assertTrue(count($people) == 2, "Error retrieving all objects.");
    }

    public function testGetMany() {
        $xpdo= $this->getXPDOObject();
        try {
            $person= $xpdo->getObject('Person', 2);
            if ($person) {
                $collPersonPhone= $person->getMany('PersonPhone');
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!empty($collPersonPhone) && $collPersonPhone['2-1'] instanceof PersonPhone, "Error retrieving related objects with getMany().");
        $this->assertTrue(!empty($collPersonPhone) && count($collPersonPhone) == 2, "Error retrieving proper objects with getMany().");
    }

    public function testRemoveObject() {
       return true;
        $result= false;
        $xpdo= $this->getXPDOObject();
        try {
            if ($person= $xpdo->getObject('Person', 1)) {
                $result= $person->remove();
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    public function testRemoveDependentObject() {
       // return true;
        $xpdo= $this->getXPDOObject();
        $result= false;
        try {
            if ($phone= $xpdo->getObject('Phone', 1)) {
                $result= $phone->remove();
            }
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error removing data.");
    }

    public function testRemoveCircularComposites() {
       // return true;
        $xpdo= $this->getXPDOObject();
        $result= false;
        try {
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
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error removing objects with circular composite relationships.");
    }

    public function testDoNotOverwriteSourceContainer() {
       // return true;
        // by default, if the connection fails, it should just error out
        // Should be an error set since we gave bogus info
        $xpdo= $this->getXPDOObject();
        try {
            $xpdo->getManager();
            $driver= $this->properties['xpdo_driver'];
            $result= $xpdo->manager->createSourceContainer(
                    $this->properties[$driver . '_string_dsn_test'],
                    $this->properties[$driver . '_string_username'],
                    $this->properties[$driver . '_string_password']
            );
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == false, "Error testing overwriting source container with createSourceContainer() method");
    }

    public function testRemoveSourceContainer() {
       // return true;
        $xpdo= $this->getXPDOObject();
        try {
            $xpdo->getManager();
            $result= $xpdo->manager->removeSourceContainer();
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue($result == true, "Error code not being set on PDO object");
    }
}

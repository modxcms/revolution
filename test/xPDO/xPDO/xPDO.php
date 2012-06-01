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
 * Tests related to basic xPDO methods
 *
 * @package xpdo-test
 * @subpackage xpdo
 */
class xPDOTest extends xPDOTestCase {
    /**
     * Verify xPDO::connect works.
     */
    public function testConnect() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $connect = $this->xpdo->connect();
        $this->assertTrue($connect,'xPDO could not connect via xpdo->connect().');
    }

    /**
     * Test table creation.
     */
    public function testCreateObjectContainer() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $this->xpdo->getManager();
            $result[] = $this->xpdo->manager->createObjectContainer('Person');
            $result[] = $this->xpdo->manager->createObjectContainer('Phone');
            $result[] = $this->xpdo->manager->createObjectContainer('PersonPhone');
            $result[] = $this->xpdo->manager->createObjectContainer('BloodType');
            $result[] = $this->xpdo->manager->createObjectContainer('Item');
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $result = !array_search(false, $result, true);
        $this->assertTrue($result, 'Error creating tables.');
    }

    /**
     * Tests xPDO::escape
     */
    public function testEscape() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $correct = 'test';
        $correct = trim($correct, $this->xpdo->_escapeCharOpen . $this->xpdo->_escapeCharClose);
        $correct = $this->xpdo->_escapeCharOpen . $correct . $this->xpdo->_escapeCharClose;

        $eco = $this->xpdo->_escapeCharOpen;
        $ecc = $this->xpdo->_escapeCharClose;
        $this->assertEquals($correct,$this->xpdo->escape('test'),'xpdo->escape() did not correctly escape.');
        $this->assertEquals($correct,$this->xpdo->escape($eco.'test'),'xpdo->escape() did not strip the beginning escape character before escaping.');
        $this->assertEquals($correct,$this->xpdo->escape($eco.'test'.$ecc),'xpdo->escape() did not strip the beginning and end escape character before escaping.');
        $this->assertEquals($correct,$this->xpdo->escape('test'.$ecc),'xpdo->escape() did not strip the end escape character before escaping.');
    }

    /**
     * Test xPDO::escSplit
     */
    public function testEscSplit() {        
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $str = '1,2,3';
        $result = xPDO::escSplit(',',$str,$this->xpdo->_escapeCharOpen);
        $this->assertTrue(is_array($result),'xPDO::escSplit did not return an array.');
        $this->assertEquals(3,count($result),'xPDO::escSplit did not return the correct number of indices.');
    }

    /**
     * Test xPDO::fromJSON
     */
    public function testFromJson() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $json = '{"key":"value","nested":{"foo":"123","another":"test"}}';
        $result = $this->xpdo->fromJSON($json);
        $this->assertTrue(is_array($result),'xpdo->fromJSON() did not return an array.');
    }

    /**
     * Test xPDO::toJSON
     */
    public function testToJson() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $array = array(
            'key' => 'value',
            'nested' => array(
                'foo' => '123',
                'another' => 'test',
            ),
        );
        $result = $this->xpdo->toJSON($array);
        $this->assertTrue(is_string($result),'xpdo->fromJSON() did not return an array.');
    }

    /**
     * Test xPDO::getManager
     */
    public function testGetManager() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $manager = $this->xpdo->getManager();
        $success = is_object($manager) && $manager instanceof xPDOManager;
        $this->assertTrue($success,'xpdo->getManager did not return an xPDOManager instance.');
    }

    /**
     * Test xPDO::getDriver
     */
    public function testGetDriver() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $driver = $this->xpdo->getDriver();
        $success = is_object($driver) && $driver instanceof xPDODriver;
        $this->assertTrue($success,'xpdo->getDriver did not return an xPDODriver instance.');
    }

    /**
     * Test xPDO::getCacheManager
     */
    public function testGetCacheManager() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $cacheManager = $this->xpdo->getCacheManager();
        $success = is_object($cacheManager) && $cacheManager instanceof xPDOCacheManager;
        $this->assertTrue($success,'xpdo->getCacheManager did not return an xPDOCacheManager instance.');
    }

    /**
     * Test xPDO::getCachePath
     */
    public function testGetCachePath() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $cachePath = $this->xpdo->getCachePath();
        $this->assertEquals($cachePath, xPDOTestHarness::$properties['xpdo_test_path'].'cache/','xpdo->getCachePath() did not return the correct cache path.');
    }
    
    /**
     * Verify xPDO::newQuery returns a xPDOQuery object
     */
    public function testNewQuery() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $criteria = $this->xpdo->newQuery('Person');
        $success = is_object($criteria) && $criteria instanceof xPDOQuery;
        $this->assertTrue($success);
    }

    /**
     * Tests xPDO::getAncestry and make sure it returns an array of the correct
     * data.
     * 
     * @dataProvider providerGetAncestry
     */
    public function testGetAncestry($class,array $correct = array(),$includeSelf = true) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $anc = $this->xpdo->getAncestry($class,$includeSelf);
        $diff = array_diff($correct,$anc);
        $diff2 = array_diff($anc,$correct);
        $success = is_array($anc) && empty($diff) && empty($diff2);
        $this->assertTrue($success);
    }
    /**
     * Data provider for testGetAncestry
     */
    public function providerGetAncestry() {
        return array(
            array('Person',array('Person','xPDOSimpleObject','xPDOObject')),
            array('Person',array('xPDOSimpleObject','xPDOObject'),false),
        );
    }

    /**
     * Tests xPDO::getDescendants and make sure it returns an array of the correct
     * data.
     * 
     * @dataProvider providerGetDescendants
     */
    public function testGetDescendants($class,array $correct = array()) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $derv = $this->xpdo->getDescendants($class);
        $diff = array_diff($correct,$derv);
        $diff2 = array_diff($derv,$correct);
        $success = is_array($derv) && empty($diff) && empty($diff2);
        $this->assertTrue($success);
    }
    /**
     * Data provider for testGetDescendants
     */
    public function providerGetDescendants() {
        return array(
            array('xPDOSimpleObject',array (
              0 => 'Person',
              1 => 'Phone',
              2 => 'xPDOSample',
              3 => 'Item',
            )),
            array('xPDOObject',array (
              0 => 'xPDOSimpleObject',
              1 => 'PersonPhone',
              2 => 'BloodType',
              3 => 'Person',
              4 => 'Phone',
              5 => 'xPDOSample',
              6 => 'Item',
            )),
        );
    }

    /**
     * Test xPDO->getSelectColumns.
     *
     * $className, $tableAlias= '', $columnPrefix= '', $columns= array (), $exclude= false
     */
    public function testGetSelectColumns() {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
    	$fields = array('id', 'first_name', 'last_name', 'middle_name', 'date_modified', 'dob', 'gender', 'blood_type', 'username', 'password', 'security_level');
        $correct = implode(', ', array_map(array($this->xpdo, 'escape'), $fields));
        $columns = $this->xpdo->getSelectColumns('Person');
        $this->assertEquals($columns,$correct);

        $correct = implode(', ', array_map(array($this, 'prefixWithEscapedPersonAlias'), $fields));
        $columns = $this->xpdo->getSelectColumns('Person','Person');
        $this->assertEquals($columns,$correct);

        $correct = implode(', ', array_map(array($this, 'postfixWithEscapedTestAlias'), $fields));
        $columns = $this->xpdo->getSelectColumns('Person','Person','test_');
        $this->assertEquals($columns,$correct);

        $includeColumns = array('id','last_name','dob');
        $correct = implode(', ', array_map(array($this->xpdo, 'escape'), $includeColumns));
        $columns = $this->xpdo->getSelectColumns('Person','','',$includeColumns);
        $this->assertEquals($columns,$correct);
        
        $excludeColumns = array('first_name','middle_name','dob','gender','security_level','blood_type');
        $correct = implode(', ', array_map(array($this->xpdo, 'escape'), array('id', 'last_name', 'date_modified', 'username', 'password')));
        $columns = $this->xpdo->getSelectColumns('Person','','',$excludeColumns,true);
        $this->assertEquals($columns,$correct);
    }
    private function prefixWithEscapedPersonAlias($string) {
    	return $this->xpdo->escape('Person') . '.' . $this->xpdo->escape($string);
    }
    private function postfixWithEscapedTestAlias($string) {
    	return $this->prefixWithEscapedPersonAlias($string) . ' AS ' . $this->xpdo->escape('test_' . $string);
    }

    /**
     * Test xPDO->getPackage.
     * 
     * @dataProvider providerGetPackage
     * @param string $class The class to test.
     * @param string $correctMeta The correct table package name that should be returned.
     */
    public function testGetPackage($class,$correctPackage) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $package = $this->xpdo->getPackage($class);
        $this->assertEquals($correctPackage,$package);
    }
    /**
     * Data provider for testGetPackage
     * @see testGetPackage
     */
    public function providerGetPackage() {
        return array(
            array('Person','sample'),
        );
    }

    /**
     * Test xPDO->getTableMeta
     * 
     * @dataProvider providerGetTableMeta
     * @param string $class The class to test.
     * @param array/null $correctMeta The correct table meta that should be returned.
     */
    public function testGetTableMeta($class,$correctMeta = null) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $tableMeta = $this->xpdo->getTableMeta($class);
        $this->assertEquals($correctMeta,$tableMeta);
    }
    /**
     * Data provider for testGetTableMeta
     * @see testGetTableMeta
     */
    public function providerGetTableMeta() {
        return array(
            array('Person',null),
        );
    }

    /**
     * Test xPDO->getFields
     * 
     * @dataProvider providerGetFields
     * @param string $class The name of the class to test.
     * @param array $correctFields An array of fields that should result.
     */
    public function testGetFields($class,array $correctFields = array()) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $fields = $this->xpdo->getFields($class);
        $diff = array_diff($fields,$correctFields);
        $diff2 = array_diff($correctFields,$fields);
        $success = is_array($fields) && empty($diff) && empty($diff2);
        $this->assertEquals($correctFields, $fields);
    }
    /**
     * Data provider for testGetFields
     * @see testGetFields
     */
    public function providerGetFields() {
        return array(
            array('Person', array(
                'id' => null,
                'first_name' => '',
                'last_name' => '',
                'middle_name' => '',
                'date_modified' => 'CURRENT_TIMESTAMP',
                'dob' => '',
                'gender' => '',
                'blood_type' => null,
                'username' => '',
                'password' => '',
                'security_level' => 1,
            )),
            array('xPDOSample', array(
                'id' => NULL,
                'parent' => 0,
                'unique_varchar' => NULL,
                'varchar' => NULL,
                'text' => NULL,
                'timestamp' => 'CURRENT_TIMESTAMP',
                'unix_timestamp' => 0,
                'date_time' => NULL,
                'date' => NULL,
                'enum' => NULL,
                'password' => NULL,
                'integer' => NULL,
                'float' => 1.01230,
                'boolean' => NULL,
            )),
        );
    }

    /**
     * Test xPDO->getFieldMeta
     *
     * @dataProvider providerGetFieldMeta
     * @param string $class The class to test.
     */
    public function testGetFieldMeta($class) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $tableMeta = $this->xpdo->getFieldMeta($class);
        $this->assertTrue(is_array($tableMeta));
    }
    /**
     * Data provider for testGetFieldMeta
     * @see testGetTableMeta
     */
    public function providerGetFieldMeta() {
        return array(
            array('Person'),
        );
    }

    /**
     * Test xPDO->getPK
     * 
     * @dataProvider providerGetPK
     * @param string $class The class name to check.
     * @param string $correctPk The PK that should result.
     */
    public function testGetPK($class,$correctPk) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $pk = $this->xpdo->getPK($class);
        $this->assertEquals($correctPk,$pk);
    }
    /**
     * Data provider for testGetPK
     * @see testGetPK
     */
    public function providerGetPK() {
        return array(
            array('Person','id'),
            array('Phone','id'),
            array('PersonPhone',array('person' => 'person','phone' => 'phone')),
        );
    }

    /**
     * Tests xPDO->getPKType
     * 
     * @dataProvider providerGetPKType
     * @param string $class
     * @param string $correctType
     */
    public function testGetPKType($class,$correctType = 'integer') {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $type = $this->xpdo->getPKType($class);
        $this->assertEquals($correctType,$type);
    }
    /**
     * Data provider for testGetPKType
     * @see testGetPKType
     */
    public function providerGetPKType() {
        return array(
            array('Person','integer'),
            array('Phone','integer'),
            array('PersonPhone',array('person' => 'integer','phone' => 'integer')),
        );
    }

    /**
     * Test xPDO->getAggregates
     *
     * @dataProvider providerGetAggregates
     * @param string $class
     * @param array $correctAggs
     */
    public function testGetAggregates($class,$correctAggs) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $aggs = $this->xpdo->getAggregates($class);
        $this->assertEquals($correctAggs,$aggs);
    }
    /**
     * Data provider for testGetAggregates
     * @see testGetAggregates
     */
    public function providerGetAggregates() {
        return array(
            array('Person',array(
                'BloodType' => array(
                    'class' => 'BloodType',
                    'local' => 'blood_type',
                    'foreign' => 'type',
                    'cardinality' => 'one',
                    'owner' => 'foreign',
                ),
            )),
            array('Phone',array()),
            array('PersonPhone',array (
                'Person' => array(
                    'class' => 'Person',
                    'local' => 'person',
                    'foreign' => 'id',
                    'cardinality' => 'one',
                    'owner' => 'foreign',
                ),
            )),
        );
    }

    /**
     * Test xPDO->getComposites
     *
     * @dataProvider providerGetComposites
     * @param string $class
     * @param array $correctComps
     */
    public function testGetComposites($class,$correctComps) {
    	if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $comps = $this->xpdo->getComposites($class);
        $this->assertEquals($correctComps,$comps);
    }
    /**
     * Data provider for testGetComposites
     * @see testGetComposites
     */
    public function providerGetComposites() {
        return array(
            array('Person',array(
              'PersonPhone' => array(
                'class' => 'PersonPhone',
                'local' => 'id',
                'foreign' => 'person',
                'cardinality' => 'many',
                'owner' => 'local',
              ),
            )),
            array('Phone',array(
              'PersonPhone' => array(
                'class' => 'PersonPhone',
                'local' => 'id',
                'foreign' => 'phone',
                'cardinality' => 'many',
                'owner' => 'local',
              ),
            )),
            array('PersonPhone',array (
              'Phone' =>
              array (
                'class' => 'Phone',
                'local' => 'phone',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
              ),
            )),
        );
    }

    /**
     * Test xPDO->getGraph()
     *
     * @dataProvider providerGetGraph
     * @param string $class The class to get a graph for.
     * @param int $depth The depth to get the graph to.
     * @param array $expected The expected graph array.
     */
    public function testGetGraph($class, $depth, $expected) {
        $actual = $this->xpdo->getGraph($class, $depth);
        $this->assertEquals($expected, $actual);
    }
    public function providerGetGraph() {
        return array(
            array('Person', 10, array('BloodType' => array(), 'PersonPhone' => array('Phone' => array()))),
            array('Person', 1, array('BloodType' => array(), 'PersonPhone' => array())),
            array('Person', 0, array()),
            array('Person', 1000, array('BloodType' => array(), 'PersonPhone' => array('Phone' => array()))),
        );
    }

    /**
     * Test xPDO->call()
     */
    public function testCall() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        $results = array();
        try {
            $results[] = ($this->xpdo->call('Item', 'callTest') === 'Item_' . $this->xpdo->getOption('dbtype'));
            $results[] = ($this->xpdo->call('xPDOSample', 'callTest') === 'xPDOSample');
            $results[] = ($this->xpdo->call('TransientDerivative', 'callTest', array(), true) === 'TransientDerivative');
            $results[] = ($this->xpdo->call('Transient', 'callTest', array(), true) === 'Transient');
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $this->assertTrue(!array_search(false, $results, true), 'Error using call()');
    }

    /**
     * Test table destruction.
     */
    public function testRemoveObjectContainer() {
        if (!empty(xPDOTestHarness::$debug)) print "\n" . __METHOD__ . " = ";
        try {
            $this->xpdo->getManager();
            $result[] = $this->xpdo->manager->removeObjectContainer('Person');
            $result[] = $this->xpdo->manager->removeObjectContainer('Phone');
            $result[] = $this->xpdo->manager->removeObjectContainer('PersonPhone');
            $result[] = $this->xpdo->manager->removeObjectContainer('BloodType');
            $result[] = $this->xpdo->manager->removeObjectContainer('Item');
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $result = !array_search(false, $result, true);
        $this->assertTrue($result, 'Error dropping tables.');
    }
}
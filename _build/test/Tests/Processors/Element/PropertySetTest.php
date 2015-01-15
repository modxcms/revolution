<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2014 by MODX, LLC.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package modx-test
 */
/**
 * Tests related to element/propertyset/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Element
 * @group PropertySet
 * @group PropertySetProcessors
 */
class PropertySetProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'element/propertyset/';

    /**
     * Setup some basic data for this test.
     */
    public function setUp() {
        parent::setUp();
        /** @var modPropertySet $propertySet */
        $propertySet = $this->modx->newObject('modPropertySet');
        $propertySet->fromArray(array('name' => 'UnitTestPropertySet'));
        $propertySet->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        parent::tearDown();
        $propertySets = $this->modx->getCollection('modPropertySet',array('name:LIKE' => '%UnitTest%'));
        /** @var modPropertySet $propertySet */
        foreach ($propertySets as $propertySet) {
            $propertySet->remove();
        }
        $this->modx->error->reset();
    }

    /**
     * Tests the element/propertyset/create processor, which creates a PropertySet
     *
     * @param boolean $shouldPass
     * @param string $propertySetPk
     * @dataProvider providerPropertySetCreate
     */
    public function testPropertySetCreate($shouldPass,$propertySetPk) {
        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'name' => $propertySetPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modPropertySet',array('name' => $propertySetPk));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create PropertySet: `'.$propertySetPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/propertyset/create processor test.
     * @return array
     */
    public function providerPropertySetCreate() {
        return array(
            array(true,'UnitTestPropertySet2'), /* pass: 1st propertyset */
            array(true,'UnitTestPropertySet3'), /* pass: 2nd propertyset */
            array(false,'UnitTestPropertySet'), /* fail: already exists */
            array(false,''), /* fail: no data */
        );
    }


    /**
     * Tests the element/propertyset/duplicate processor, which duplicates a PropertySet
     * @param boolean $shouldPass
     * @param string $propertySetPk
     * @param string $newName
     * @depends testPropertySetCreate
     * @dataProvider providerPropertySetDuplicate
     */
    public function testPropertySetDuplicate($shouldPass,$propertySetPk,$newName) {
        $propertySet = $this->modx->getObject('modPropertySet',array('name' => $propertySetPk));
        if (empty($propertySet) && $shouldPass) {
            $this->fail('No PropertySet found "'.$propertySetPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'duplicate',array(
            'id' => $propertySet ? $propertySet->get('id') : $propertySetPk,
            'new_name' => $newName,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'duplicate processor');
            return;
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getObject('modPropertySet',array('name' => $newName));
        $passed = $s && $ct;
        $passed = $shouldPass ? $passed : !$passed;
        if ($ct) { /* remove test data */
            $ct->remove();
        }
        $this->assertTrue($passed,'Could not duplicate PropertySet: `'.$propertySetPk.'` to `'.$newName.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/propertyset/duplicate processor test.
     * @return array
     */
    public function providerPropertySetDuplicate() {
        return array(
            array(true,'UnitTestPropertySet','UnitTestPropertySet3'), /* pass: standard name */
            array(false,'UnitTestPropertySet',''), /* pass: with blank name */
            array(false,'',''), /* fail: no data */
            //array(false,'','UnitTestPropertySet3'), /* fail: blank propertyset to duplicate */
        );
    }
    /**
     * Tests the element/propertyset/get processor, which gets a PropertySet
     *
     * @param boolean $shouldPass
     * @param string $propertySetPk
     * @depends testPropertySetCreate
     * @dataProvider providerPropertySetGet
     */
    public function testPropertySetGet($shouldPass,$propertySetPk) {
        $propertyset = $this->modx->getObject('modPropertySet',array('name' => $propertySetPk));
        if (empty($propertyset) && $shouldPass) {
            $this->fail('No PropertySet found "'.$propertySetPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'id' => $propertyset ? $propertyset->get('id') : $propertySetPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get PropertySet: `'.$propertySetPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/propertyset/create processor test.
     * @return array
     */
    public function providerPropertySetGet() {
        return array(
            array(true,'UnitTestPropertySet'), /* pass: get first propertyset */
            array(false,234), /* fail: invalid ID */
            array(false,''), /* fail: no data */
        );
    }

    /**
     * Attempts to get a list of propertysets
     *
     * @param boolean $shouldPass
     * @param string $sort
     * @param string $dir
     * @param int $limit
     * @param int $start
     * @depends testPropertySetCreate
     * @dataProvider providerPropertySetGetList
     */
    public function testPropertySetGetList($shouldPass = true,$sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getlist',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        $results = $this->getResults($result);
        $passed = !empty($results);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get list of PropertySets: '.$result->getMessage());
    }
    /**
     * Data provider for element/propertyset/getlist processor test.
     * @return array
     */
    public function providerPropertySetGetList() {
        return array(
            array(true,'name','ASC',5,0), /* pass: get first 5 sorted by name ASC */
            array(true,'name','DESC',5,0), /* pass: get first 5 sorted by name DESC */
            array(false,'zzz','ASC',5,0), /* fail: invalid sort column */
            array(false,'name','ASC',5,5), /* fail: start beyond the total # of propertysets */
        );
    }

    /**
     * Tests the element/propertyset/remove processor, which removes a PropertySet
     * @param boolean $shouldPass
     * @param string $propertySetPk
     * @depends testPropertySetCreate
     * @dataProvider providerPropertySetRemove
     */
    public function testPropertySetRemove($shouldPass,$propertySetPk) {
        $propertyset = $this->modx->getObject('modPropertySet',array('name' => $propertySetPk));
        if (empty($propertyset) && $shouldPass) {
            $this->fail('No PropertySet found "'.$propertySetPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $propertyset ? $propertyset->get('id') : $propertySetPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove PropertySet: `'.$propertySetPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/propertyset/remove processor test.
     * @return array
     */
    public function providerPropertySetRemove() {
        return array(
            array(true,'UnitTestPropertySet'), /* pass: remove first propertyset */
            array(false,234), /* fail: invalid ID */
            array(false,''), /* fail: no data */
        );
    }
}

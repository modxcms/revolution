<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
*/
namespace MODX\Revolution\Tests\Processors\Element;


use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\modPropertySet;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Element\PropertySet\Create;
use MODX\Revolution\Processors\Element\PropertySet\Duplicate;
use MODX\Revolution\Processors\Element\PropertySet\Get;
use MODX\Revolution\Processors\Element\PropertySet\GetList;
use MODX\Revolution\Processors\Element\PropertySet\Remove;

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
    public function setUp() {
        parent::setUp();
        /** @var modPropertySet $propertySet */
        $propertySet = $this->modx->newObject(modPropertySet::class);
        $propertySet->fromArray(['name' => 'UnitTestPropertySet']);
        $propertySet->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        parent::tearDown();
        $propertySets = $this->modx->getCollection(modPropertySet::class, ['name:LIKE' => '%UnitTest%']);
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
        /** @var ProcessorResponse $result */
        $result = $this->modx->runProcessor(Create::class, [
            'name' => $propertySetPk,
        ]);
        if (empty($result)) {
            $this->fail('Could not load '.Create::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount(modPropertySet::class, ['name' => $propertySetPk]);
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create PropertySet: `'.$propertySetPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/propertyset/create processor test.
     * @return array
     */
    public function providerPropertySetCreate() {
        return [
            [true,'UnitTestPropertySet2'], /* pass: 1st propertyset */
            [true,'UnitTestPropertySet3'], /* pass: 2nd propertyset */
            [false,'UnitTestPropertySet'], /* fail: already exists */
            [false,''], /* fail: no data */
        ];
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
	    $this->markTestSkipped(
		    'The test is skipped - testPropertySetDuplicate.'
	    );
	    return;

        $propertySet = $this->modx->getObject(modPropertySet::class, ['name' => $propertySetPk]);
        if (empty($propertySet) && $shouldPass) {
            $this->fail('No PropertySet found "'.$propertySetPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(Duplicate::class, [
            'id' => $propertySet ? $propertySet->get('id') : $propertySetPk,
            'new_name' => $newName,
        ]);
        if (empty($result)) {
            $this->fail('Could not load '.Duplicate::class.' processor');
            return;
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getObject(modPropertySet::class, ['name' => $newName]);
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
        return [
            [true,'UnitTestPropertySet','UnitTestPropertySet3'], /* pass: standard name */
            [false,'UnitTestPropertySet',''], /* pass: with blank name */
            [false,'',''], /* fail: no data */
            //array(false,'','UnitTestPropertySet3'), /* fail: blank propertyset to duplicate */
        ];
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
        $propertyset = $this->modx->getObject(modPropertySet::class, ['name' => $propertySetPk]);
        if (empty($propertyset) && $shouldPass) {
            $this->fail('No PropertySet found "'.$propertySetPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(Get::class, [
            'id' => $propertyset ? $propertyset->get('id') : $propertySetPk,
        ]);
        if (empty($result)) {
            $this->fail('Could not load '.Get::class.' processor');
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
        return [
            [true,'UnitTestPropertySet'], /* pass: get first propertyset */
            [false,234], /* fail: invalid ID */
            [false,''], /* fail: no data */
        ];
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
        $result = $this->modx->runProcessor(GetList::class, [
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ]);
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
        return [
            [true,'name','ASC',5,0], /* pass: get first 5 sorted by name ASC */
            [true,'name','DESC',5,0], /* pass: get first 5 sorted by name DESC */
            [false,'zzz','ASC',5,0], /* fail: invalid sort column */
            [false,'name','ASC',5,5], /* fail: start beyond the total # of propertysets */
        ];
    }

    /**
     * Tests the element/propertyset/remove processor, which removes a PropertySet
     * @param boolean $shouldPass
     * @param string $propertySetPk
     * @depends testPropertySetCreate
     * @dataProvider providerPropertySetRemove
     */
    public function testPropertySetRemove($shouldPass,$propertySetPk) {
        $propertyset = $this->modx->getObject(modPropertySet::class, ['name' => $propertySetPk]);
        if (empty($propertyset) && $shouldPass) {
            $this->fail('No PropertySet found "'.$propertySetPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(Remove::class, [
            'id' => $propertyset ? $propertyset->get('id') : $propertySetPk,
        ]);
        if (empty($result)) {
            $this->fail('Could not load '.Remove::class.' processor');
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
        return [
            [true,'UnitTestPropertySet'], /* pass: remove first propertyset */
            [false,234], /* fail: invalid ID */
            [false,''], /* fail: no data */
        ];
    }
}

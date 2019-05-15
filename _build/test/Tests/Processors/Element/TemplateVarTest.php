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


use MODX\Revolution\modTemplateVar;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Element\PropertySet\Get;
use MODX\Revolution\Processors\Element\TemplateVar\Create;
use MODX\Revolution\Processors\Element\TemplateVar\GetList;
use MODX\Revolution\Processors\Element\TemplateVar\Remove;

/**
 * Tests related to element/tv/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Element
 * @group TemplateVar
 * @group TemplateVarProcessors
 */
class TemplateVarProcessorsTest extends MODxTestCase {
    public function setUp() {
        parent::setUp();
        /** @var modTemplateVar $tv */
        $tv = $this->modx->newObject(modTemplateVar::class);
        $tv->fromArray(array('name' => 'UnitTestTv'));
        $tv->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        $tvs = $this->modx->getCollection(modTemplateVar::class,array('name:LIKE' => '%UnitTest%'));
        /** @var modTemplateVar $tv */
        foreach ($tvs as $tv) {
            $tv->remove();
        }
        $this->modx->error->reset();
    }

    /**
     * Tests the element/tv/create processor, which creates a TV
     *
     * @param boolean $shouldPass
     * @param string $tvPk
     * @dataProvider providerTvCreate
     */
    public function testTvCreate($shouldPass,$tvPk) {
        if (empty($tvPk)) return;
        $result = $this->modx->runProcessor(Create::class,array(
            'name' => $tvPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Create::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount(modTemplateVar::class,array('name' => $tvPk));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create Tv: `'.$tvPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/tv/create processor test.
     *
     * @return array
     */
    public function providerTvCreate() {
        return array(
            array(true,'UnitTestTv2'),
            array(true,'UnitTestTv3'),
            array(false,'UnitTestTv'),
        );
    }

    /**
     * Tests the element/tv/get processor, which gets a Tv
     *
     * @param boolean $shouldPass
     * @param string $tvPk
     * @dataProvider providerTvGet
     */
    public function testTvGet($shouldPass,$tvPk) {
        if (empty($tvPk)) return;

        $tv = $this->modx->getObject(modTemplateVar::class,array('name' => $tvPk));
        if (empty($tv) && $shouldPass) {
            $this->fail('No Tv found "'.$tvPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(Get::class,array(
            'id' => $tv ? $tv->get('id') : $tvPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Get::class.' processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Tv: `'.$tvPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/tv/create processor test.
     * @return array
     */
    public function providerTvGet() {
        return array(
            array(true,'UnitTestTv'),
            array(false,234),
        );
    }

    /**
     * Attempts to get a list of Template Variables
     *
     * @param string $sort
     * @param string $dir
     * @param int $limit
     * @param int $start
     * @dataProvider providerTvGetList
     */
    public function testTvGetList($sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(GetList::class,array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        $results = $this->getResults($result);
        $this->assertTrue(!empty($results),'Could not get list of TVs: '.$result->getMessage());
    }
    /**
     * Data provider for element/tv/getlist processor test.
     * @return array
     */
    public function providerTvGetList() {
        return array(
            array('name','ASC',5,0),
        );
    }

    /**
     * Tests the element/tv/remove processor, which removes a Tv
     *
     * @param boolean $shouldPass
     * @param string $tvPk
     * @dataProvider providerTvRemove
     */
    public function testTvRemove($shouldPass,$tvPk) {
        if (empty($tvPk)) return;

        $tv = $this->modx->getObject(modTemplateVar::class,array('name' => $tvPk));
        if (empty($tv) && $shouldPass) {
            $this->fail('No Tv found "'.$tvPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(Remove::class,array(
            'id' => $tv ? $tv->get('id') : $tvPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Remove::class.' processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Tv: `'.$tvPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/tv/remove processor test.
     * @return array
     */
    public function providerTvRemove() {
        return array(
            array(true,'UnitTestTv'),
            array(false,234),
        );
    }
}

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
    const PROCESSOR_LOCATION = 'element/tv/';

    /**
     * Setup some basic data for this test.
     */
    public function setUp() {
        parent::setUp();
        /** @var modTemplateVar $tv */
        $tv = $this->modx->newObject('modTemplateVar');
        $tv->fromArray(array('name' => 'UnitTestTv'));
        $tv->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        $tvs = $this->modx->getCollection('modTemplateVar',array('name:LIKE' => '%UnitTest%'));
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
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'name' => $tvPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modTemplateVar',array('name' => $tvPk));
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

        $tv = $this->modx->getObject('modTemplateVar',array('name' => $tvPk));
        if (empty($tv) && $shouldPass) {
            $this->fail('No Tv found "'.$tvPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'id' => $tv ? $tv->get('id') : $tvPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
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
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getlist',array(
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

        $tv = $this->modx->getObject('modTemplateVar',array('name' => $tvPk));
        if (empty($tv) && $shouldPass) {
            $this->fail('No Tv found "'.$tvPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $tv ? $tv->get('id') : $tvPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
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

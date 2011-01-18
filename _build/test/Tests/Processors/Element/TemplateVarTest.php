<?php
/**
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
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
    public static function setUpBeforeClass() {
        $modx = MODxTestHarness::_getConnection();
        $modx->error->reset();
        $tv = $modx->getObject('modTemplateVar',array('name' => 'UnitTestTv'));
        if ($tv) $tv->remove();
        $tv = $modx->getObject('modTemplateVar',array('name' => 'UnitTestTv2'));
        if ($tv) $tv->remove();
    }

    /**
     * Cleanup data after this test.
     */
    public static function tearDownAfterClass() {
        $modx = MODxTestHarness::_getConnection();
        $tv = $modx->getObject('modTemplateVar',array('name' => 'UnitTestTv'));
        if ($tv) $tv->remove();
        $tv = $modx->getObject('modTemplateVar',array('name' => 'UnitTestTv2'));
        if ($tv) $tv->remove();
    }

    /**
     * Tests the element/tv/create processor, which creates a Tv
     * @dataProvider providerTvCreate
     */
    public function testTvCreate($shouldPass,$tvPk) {
        if (empty($tvPk)) return false;
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
     */
    public function providerTvCreate() {
        return array(
            array(true,'UnitTestTv'),
            array(true,'UnitTestTv2'),
            array(false,'UnitTestTv2'),
        );
    }

    /**
     * Tests the element/tv/get processor, which gets a Tv
     * @dataProvider providerTvGet
     */
    public function testTvGet($shouldPass,$tvPk) {
        if (empty($tvPk)) return false;

        $tv = $this->modx->getObject('modTemplateVar',array('name' => $tvPk));
        if (empty($tv) && $shouldPass) {
            $this->fail('No Tv found "'.$tvPk.'" as specified in test provider.');
            return false;
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
     */
    public function providerTvGet() {
        return array(
            array(true,'UnitTestTv'),
            array(false,234),
        );
    }

    /**
     * Tests the element/tv/remove processor, which removes a Tv
     * @dataProvider providerTvRemove
     */
    public function testTvRemove($shouldPass,$tvPk) {
        if (empty($tvPk)) return false;

        $tv = $this->modx->getObject('modTemplateVar',array('name' => $tvPk));
        if (empty($tv) && $shouldPass) {
            $this->fail('No Tv found "'.$tvPk.'" as specified in test provider.');
            return false;
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
     */
    public function providerTvRemove() {
        return array(
            array(true,'UnitTestTv'),
            array(false,234),
        );
    }
}
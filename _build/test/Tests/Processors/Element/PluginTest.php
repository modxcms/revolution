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
 * Tests related to element/plugin/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Element
 * @group Plugin
 * @group PluginProcessors
 */
class PluginProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'element/plugin/';

    /**
     * Setup some basic data for this test.
     */
    public function setUp() {
        parent::setUp();
        /** @var modPlugin $plugin */
        $plugin = $this->modx->newObject('modPlugin');
        $plugin->fromArray(array(
            'name' => 'UnitTestPlugin'
        ));
        $plugin->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        parent::tearDown();
        $plugins = $this->modx->getCollection('modPlugin',array('name:LIKE' => '%UnitTest%'));
        /** @var modPlugin $plugin */
        foreach ($plugins as $plugin) {
            $plugin->remove();
        }
        $this->modx->error->reset();
    }

    /**
     * Tests the element/plugin/create processor, which creates a Plugin
     * @param boolean $shouldPass
     * @param string $pluginPk
     * @dataProvider providerPluginCreate
     */
    public function testPluginCreate($shouldPass,$pluginPk) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'name' => $pluginPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modPlugin',array('name' => $pluginPk));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create Plugin: `'.$pluginPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/plugin/create processor test.
     * @return array
     */
    public function providerPluginCreate() {
        return array(
            array(true,'UnitTestPlugin2'), /* pass: 1st plugin */
            array(true,'UnitTestPlugin3'), /* pass: 2nd plugin */
            array(false,'UnitTestPlugin'), /* fail: already exists */
            array(false,''), /* fail: no data */
        );
    }


    /**
     * Tests the element/plugin/duplicate processor, which duplicates a Plugin
     * @param boolean $shouldPass
     * @param string $pluginPk
     * @param string $newName
     * @dataProvider providerPluginDuplicate
     */
    public function testPluginDuplicate($shouldPass,$pluginPk,$newName) {
        $plugin = $this->modx->getObject('modPlugin',array('name' => $pluginPk));
        if (empty($plugin) && $shouldPass) {
            $this->fail('No Plugin found "'.$pluginPk.'" as specified in test provider.');
            return;
        }
        $this->modx->lexicon->load('default');

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'duplicate',array(
            'id' => $plugin ? $plugin->get('id') : $pluginPk,
            'name' => $newName,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'duplicate processor');
        }
        $s = $this->checkForSuccess($result);
        if (empty($newName) && $plugin) {
            $newName = $this->modx->lexicon('duplicate_of',array('name' => $plugin->get('name')));
        }
        $ct = $this->modx->getObject('modPlugin',array('name' => $newName));
        $passed = $s && $ct;
        $passed = $shouldPass ? $passed : !$passed;
        if ($ct) { /* remove test data */
            $ct->remove();
        }
        $this->assertTrue($passed,'Could not duplicate Plugin: `'.$pluginPk.'` to `'.$newName.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/plugin/duplicate processor test.
     * @return array
     */
    public function providerPluginDuplicate() {
        return array(
            array(true,'UnitTestPlugin','UnitTestPlugin3'), /* pass: standard name */
            array(true,'UnitTestPlugin',''), /* pass: with blank name */
            array(false,'',''), /* fail: no data */
            array(false,'','UnitTestPlugin3'), /* fail: blank plugin to duplicate */
        );
    }
    /**
     * Tests the element/plugin/get processor, which gets a Plugin
     * @param boolean $shouldPass
     * @param string $pluginPk
     * @dataProvider providerPluginGet
     */
    public function testPluginGet($shouldPass,$pluginPk) {
        $plugin = $this->modx->getObject('modPlugin',array('name' => $pluginPk));
        if (empty($plugin) && $shouldPass) {
            $this->fail('No Plugin found "'.$pluginPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'id' => $plugin ? $plugin->get('id') : $pluginPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Plugin: `'.$pluginPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/plugin/create processor test.
     * @return array
     */
    public function providerPluginGet() {
        return array(
            array(true,'UnitTestPlugin'), /* pass: get first plugin */
            array(false,234), /* fail: invalid ID */
            array(false,''), /* fail: no data */
        );
    }

    /**
     * Attempts to get a list of plugins
     *
     * @param boolean $shouldPass
     * @param string $sort
     * @param string $dir
     * @param int $limit
     * @param int $start
     * @dataProvider providerPluginGetList
     */
    public function testPluginGetList($shouldPass = true,$sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getlist',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        $results = $this->getResults($result);
        $passed = !empty($results);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get list of Plugins: '.$result->getMessage());
    }
    /**
     * Data provider for element/plugin/getlist processor test.
     * @return array
     */
    public function providerPluginGetList() {
        return array(
            array(true,'name','ASC',5,0), /* pass: get first 5 sorted by name ASC */
            array(true,'name','DESC',5,0), /* pass: get first 5 sorted by name DESC */
            array(false,'zzz','ASC',5,0), /* fail: invalid sort column */
            array(false,'name','ASC',5,5), /* fail: start beyond the total # of plugins */
        );
    }

    /**
     * Tests the element/plugin/remove processor, which removes a Plugin
     *
     * @param boolean $shouldPass
     * @param string $pluginPk
     * @dataProvider providerPluginRemove
     */
    public function testPluginRemove($shouldPass,$pluginPk) {
        $plugin = $this->modx->getObject('modPlugin',array('name' => $pluginPk));
        if (empty($plugin) && $shouldPass) {
            $this->fail('No Plugin found "'.$pluginPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $plugin ? $plugin->get('id') : $pluginPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Plugin: `'.$pluginPk.'`: '.$result->getMessage());
    }
    /**
     * @return array
     * Data provider for element/plugin/remove processor test.
     */
    public function providerPluginRemove() {
        return array(
            array(true,'UnitTestPlugin'), /* pass: remove first plugin */
            array(false,234), /* fail: invalid ID */
            array(false,''), /* fail: no data */
        );
    }
}

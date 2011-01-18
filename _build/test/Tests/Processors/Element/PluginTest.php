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
    public static function setUpBeforeClass() {
        $modx = MODxTestHarness::_getConnection();
        $modx->error->reset();
        $plugin = $modx->getObject('modPlugin',array('name' => 'UnitTestPlugin'));
        if ($plugin) $plugin->remove();
        $plugin = $modx->getObject('modPlugin',array('name' => 'UnitTestPlugin2'));
        if ($plugin) $plugin->remove();
    }

    /**
     * Cleanup data after this test.
     */
    public static function tearDownAfterClass() {
        $modx = MODxTestHarness::_getConnection();
        $plugin = $modx->getObject('modPlugin',array('name' => 'UnitTestPlugin'));
        if ($plugin) $plugin->remove();
        $plugin = $modx->getObject('modPlugin',array('name' => 'UnitTestPlugin2'));
        if ($plugin) $plugin->remove();
    }

    /**
     * Tests the element/plugin/create processor, which creates a Plugin
     * @dataProvider providerPluginCreate
     */
    public function testPluginCreate($shouldPass,$pluginPk) {
        if (empty($pluginPk)) return false;
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
     */
    public function providerPluginCreate() {
        return array(
            array(true,'UnitTestPlugin'),
            array(true,'UnitTestPlugin2'),
            array(false,'UnitTestPlugin2'),
        );
    }

    /**
     * Tests the element/plugin/get processor, which gets a Plugin
     * @dataProvider providerPluginGet
     */
    public function testPluginGet($shouldPass,$pluginPk) {
        if (empty($pluginPk)) return false;

        $plugin = $this->modx->getObject('modPlugin',array('name' => $pluginPk));
        if (empty($plugin) && $shouldPass) {
            $this->fail('No Plugin found "'.$pluginPk.'" as specified in test provider.');
            return false;
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
     */
    public function providerPluginGet() {
        return array(
            array(true,'UnitTestPlugin'),
            array(false,234),
        );
    }

    /**
     * Attempts to get a list of plugins
     *
     * @dataProvider providerPluginGetList
     */
    public function testPluginGetList($sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getList',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        $results = $this->getResults($result);
        $this->assertTrue(!empty($results),'Could not get list of Plugins: '.$result->getMessage());
    }
    /**
     * Data provider for element/plugin/getlist processor test.
     */
    public function providerPluginGetList() {
        return array(
            array('name','ASC',5,0),
        );
    }

    /**
     * Tests the element/plugin/remove processor, which removes a Plugin
     * @dataProvider providerPluginRemove
     */
    public function testPluginRemove($shouldPass,$pluginPk) {
        if (empty($pluginPk)) return false;

        $plugin = $this->modx->getObject('modPlugin',array('name' => $pluginPk));
        if (empty($plugin) && $shouldPass) {
            $this->fail('No Plugin found "'.$pluginPk.'" as specified in test provider.');
            return false;
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
     * Data provider for element/plugin/remove processor test.
     */
    public function providerPluginRemove() {
        return array(
            array(true,'UnitTestPlugin'),
            array(false,234),
        );
    }
}
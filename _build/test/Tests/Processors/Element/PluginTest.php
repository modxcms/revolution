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


use MODX\Revolution\modPlugin;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Element\Plugin\Create;
use MODX\Revolution\Processors\Element\Plugin\Duplicate;
use MODX\Revolution\Processors\Element\Plugin\Get;
use MODX\Revolution\Processors\Element\Plugin\GetList;
use MODX\Revolution\Processors\Element\Plugin\Remove;

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
    public function setUp() {
        parent::setUp();
        /** @var modPlugin $plugin */
        $plugin = $this->modx->newObject(modPlugin::class);
        $plugin->fromArray(array(
            'name' => 'UnitTestPlugin'
        ));
        $plugin->save();
    }

    public function tearDown() {
        parent::tearDown();
        $plugins = $this->modx->getCollection(modPlugin::class,array('name:LIKE' => '%UnitTest%'));
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
        $result = $this->modx->runProcessor(Create::class,array(
            'name' => $pluginPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Create::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount(modPlugin::class,array('name' => $pluginPk));
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
        $plugin = $this->modx->getObject(modPlugin::class,array('name' => $pluginPk));
        if (empty($plugin) && $shouldPass) {
            $this->fail('No Plugin found "'.$pluginPk.'" as specified in test provider.');
            return;
        }
        $this->modx->lexicon->load('default');

        $result = $this->modx->runProcessor(Duplicate::class,array(
            'id' => $plugin ? $plugin->get('id') : $pluginPk,
            'name' => $newName,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Duplicate::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        if (empty($newName) && $plugin) {
            $newName = $this->modx->lexicon('duplicate_of',array('name' => $plugin->get('name')));
        }
        $ct = $this->modx->getObject(modPlugin::class,array('name' => $newName));
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
        $plugin = $this->modx->getObject(modPlugin::class,array('name' => $pluginPk));
        if (empty($plugin) && $shouldPass) {
            $this->fail('No Plugin found "'.$pluginPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(Get::class,array(
            'id' => $plugin ? $plugin->get('id') : $pluginPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Get::class.' processor');
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
        $result = $this->modx->runProcessor(GetList::class,array(
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
        $plugin = $this->modx->getObject(modPlugin::class,array('name' => $pluginPk));
        if (empty($plugin) && $shouldPass) {
            $this->fail('No Plugin found "'.$pluginPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(Remove::class,array(
            'id' => $plugin ? $plugin->get('id') : $pluginPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Remove::class.' processor');
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

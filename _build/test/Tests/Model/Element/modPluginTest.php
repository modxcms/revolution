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
namespace MODX\Revolution\Tests\Model\Element;


use MODX\Revolution\modPlugin;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modPlugin class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Element
 * @group modElement
 * @group modScript
 * @group modPlugin
 */
class modPluginTest extends MODxTestCase {
    /** @var modPlugin $plugin */
    public $plugin;

    /**
    * Setup fixtures before each test.
    *
    * @before
    */
    public function setUpFixtures() {
        parent::setUpFixtures();
        $this->plugin = $this->modx->newObject(modPlugin::class);
        $this->plugin->fromArray([
            'id' => 12345,
            'name' => 'Unit Test Plugin',
            'description' => 'A plugin for unit testing.',
            'plugincode' => 'return "Hello.";',
            'category' => 0,
            'locked' => false,
            'disabled' => false,
        ],'',true,true);
        $this->plugin->setProperties(['name' => 'John']);
        $this->plugin->setCacheable(false);
    }
    /**
    * Tear down fixtures after each test.
    *
    * @after
    */
    public function tearDownFixtures() {
        parent::tearDownFixtures();
        $this->plugin = null;
    }

    /**
    * @return void
    */
    public function testGetContent() {
        $this->assertEquals($this->plugin->get('plugincode'),$this->plugin->getContent());
    }

    /**
    * @param string $content
    * @dataProvider providerSetContent
    * @depends testGetContent
    */
    public function testSetContent($content) {
        $this->plugin->setContent($content);
        $this->assertEquals($content,$this->plugin->get('plugincode'));
    }
    /**
    * @return array
    */
    public function providerSetContent() {
        return [
            ['return "Goodbye.";'],
        ];
    }

}

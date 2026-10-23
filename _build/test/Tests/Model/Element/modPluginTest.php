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
     * Loading an unchanged static Plugin's content must not save the object.
     *
     * @return void
     */
    public function testGetUnchangedStaticContentDoesNotSetEditedon() {
        $staticFile = MODX_CORE_PATH . 'cache/unit-test-static-plugin.php';
        $plugin = $this->modx->newObject(modPlugin::class);
        $plugin->fromArray([
            'name' => 'Unit Test Static Plugin',
            'plugincode' => 'return "Static content";',
            'static' => true,
            'static_file' => $staticFile,
        ]);

        try {
            $this->assertTrue($plugin->save());
            $this->assertEmpty($plugin->get('editedon'));

            /** @var modPlugin $reloaded */
            $reloaded = $this->modx->getObject(modPlugin::class, $plugin->get('id'), false);
            $this->assertSame('return "Static content";', $reloaded->getContent());
            $this->assertEmpty($reloaded->get('editedon'));
        } finally {
            $plugin->remove();
            @unlink($staticFile);
        }
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

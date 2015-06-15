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

    public function setUp() {
        parent::setUp();
        $this->plugin = $this->modx->newObject('modPlugin');
        $this->plugin->fromArray(array(
            'id' => 12345,
            'name' => 'Unit Test Plugin',
            'description' => 'A plugin for unit testing.',
            'plugincode' => 'return "Hello.";',
            'category' => 0,
            'locked' => false,
            'disabled' => false,
        ),'',true,true);
        $this->plugin->setProperties(array('name' => 'John'));
        $this->plugin->setCacheable(false);
    }
    public function tearDown() {
        parent::tearDown();
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
        return array(
            array('return "Goodbye.";'),
        );
    }

}

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
 * Tests related to the modSnippet class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Element
 * @group modElement
 * @group modScript
 * @group modSnippet
 */
class modSnippetTest extends MODxTestCase {
    /** @var modSnippet $snippet */
    public $snippet;

    public function setUp() {
        parent::setUp();
        $this->snippet = $this->modx->newObject('modSnippet');
        $this->snippet->fromArray(array(
            'name' => 'Unit Test Snippet',
            'description' => 'A snippet for unit testing.',
            'snippet' => str_replace('<?php','',file_get_contents(MODX_BASE_PATH.'_build/test/data/snippets/modSnippetTest/modSnippetTest.snippet.php')),
            'category' => 0,
            'locked' => false,
        ),'',true,true);
        $this->snippet->setProperties(array('name' => 'John'));
        $this->snippet->setCacheable(false);
        $this->snippet->save();
        $this->modx->event= new modSystemEvent();
    }
    public function tearDown() {
        parent::tearDown();
        $this->snippet->remove();
        $this->snippet = null;
    }

    /**
     * @return void
     */
    public function testGetContent() {
        $this->assertEquals($this->snippet->get('snippet'),$this->snippet->getContent());
    }

    /**
     * @param string $content
     * @dataProvider providerSetContent
     * @depends testGetContent
     */
    public function testSetContent($content) {
        $this->snippet->setContent($content);
        $this->assertEquals($content,$this->snippet->get('snippet'));
    }
    /**
     * @return array
     */
    public function providerSetContent() {
        return array(
            array('return "Goodbye.";'),
        );
    }


    /**
     * @param string $expected
     * @param null|array $properties
     * @dataProvider providerProcess
     */
    public function testProcess($expected,$properties = null) {
        $this->snippet->setCacheable(false);
        $result = $this->snippet->process($properties);
        $this->assertEquals($expected,$result,'After processing the snippet, the expected result was different than the actual result.');
    }
    /**
     * @return array
     */
    public function providerProcess() {
        return array(
            array('Hello, John'),
            array('Hello, Mark',array('name' => 'Mark')),
        );
    }
}

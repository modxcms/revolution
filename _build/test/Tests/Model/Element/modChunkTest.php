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
 * Tests related to the modChunk class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Element
 * @group modElement
 * @group Chunk
 * @group modChunk
 */
class modChunkTest extends MODxTestCase {
    /** @var modChunk $chunk */
    public $chunk;

    public function setUp() {
        parent::setUp();
        $this->chunk = $this->modx->newObject('modChunk');
        $this->chunk->fromArray(array(
            'id' => 12345,
            'name' => 'Unit Test Chunk',
            'description' => 'A chunk for unit testing.',
            'snippet' => '<p>Hello, [[+name]]!</p>',
            'category' => 0,
            'locked' => 0,
        ),'',true,true);
        $this->chunk->setProperties(array('name' => 'John'));
        $this->chunk->setCacheable(false);
    }
    public function tearDown() {
        parent::tearDown();
        $this->chunk = null;
    }

    /**
     * @return void
     */
    public function testGetContent() {
        $this->assertEquals($this->chunk->get('snippet'),$this->chunk->getContent());
    }

    /**
     * @param string $content
     * @dataProvider providerSetContent
     * @depends testGetContent
     */
    public function testSetContent($content) {
        $this->chunk->setContent($content);
        $this->assertEquals($content,$this->chunk->get('snippet'));
    }
    /**
     * @return array
     */
    public function providerSetContent() {
        return array(
            array('Test content.'),
        );
    }

    /**
     * @param string $expected
     * @param array $properties
     * @param null|string $content
     * @dataProvider providerProcess
     */
    public function testProcess($expected,array $properties = array(),$content = null) {
        $result = $this->chunk->process($properties,$content);
        $this->assertEquals($expected,$result);
    }
    /**
     * @return array
     */
    public function providerProcess() {
        return array(
            array('<p>Hello, John!</p>'),
            array('<p>Hello, Mark!</p>',array('name' => 'Mark')),
            array('<p>Having fun.</p>',array(),'<p>Having fun.</p>'),
            array('<p>Test 1</p>',array('number' => 1),'<p>Test [[+number]]</p>'),
        );
    }
}

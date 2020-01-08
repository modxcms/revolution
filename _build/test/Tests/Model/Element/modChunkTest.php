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


use MODX\Revolution\modChunk;
use MODX\Revolution\MODxTestCase;

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
        $this->chunk = $this->modx->newObject(modChunk::class);
        $this->chunk->fromArray([
            'id' => 12345,
            'name' => 'Unit Test Chunk',
            'description' => 'A chunk for unit testing.',
            'snippet' => '<p>Hello, [[+name]]!</p>',
            'category' => 0,
            'locked' => 0,
        ],'',true,true);
        $this->chunk->setProperties(['name' => 'John']);
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
        return [
            ['Test content.'],
        ];
    }

    /**
     * @param string $expected
     * @param array $properties
     * @param null|string $content
     * @dataProvider providerProcess
     */
    public function testProcess($expected,array $properties = [],$content = null) {
        $result = $this->chunk->process($properties,$content);
        $this->assertEquals($expected,$result);
    }
    /**
     * @return array
     */
    public function providerProcess() {
        return [
            ['<p>Hello, John!</p>'],
            ['<p>Hello, Mark!</p>', ['name' => 'Mark']],
            ['<p>Having fun.</p>', [],'<p>Having fun.</p>'],
            ['<p>Test 1</p>', ['number' => 1],'<p>Test [[+number]]</p>'],
        ];
    }
}

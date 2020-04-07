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


use MODX\Revolution\modTemplate;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modTemplate class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Element
 * @group modElement
 * @group modTemplate
 */
class modTemplateTest extends MODxTestCase {
    /** @var modTemplate $template */
    public $template;

    public function setUp() {
        parent::setUp();
        $this->template = $this->modx->newObject(modTemplate::class);
        $this->template->fromArray([
            'id' => 12345,
            'templatename' => 'Unit Test Template',
            'description' => 'A template for unit testing.',
            'content' => '<p>Hello, [[+name]]!</p>',
            'category' => 0,
            'locked' => 0,
        ],'',true,true);
        $this->template->setProperties(['name' => 'John']);
        $this->template->setCacheable(false);
    }
    public function tearDown() {
        parent::tearDown();
        $this->template = null;
    }

    /**
     * @return void
     */
    public function testGetContent() {
        $this->assertEquals($this->template->get('content'),$this->template->getContent());
    }

    /**
     * @param string $content
     * @dataProvider providerSetContent
     * @depends testGetContent
     */
    public function testSetContent($content) {
        $this->template->setContent($content);
        $this->assertEquals($content,$this->template->get('content'));
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
        $result = $this->template->process($properties,$content);
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

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


use MODX\Revolution\modSnippet;
use MODX\Revolution\modSystemEvent;
use MODX\Revolution\MODxTestCase;

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

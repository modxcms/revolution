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

    /**
     * Setup fixtures before each test.
     *
     * @before
     */
    public function setUpFixtures() {
        parent::setUpFixtures();
        $this->snippet = $this->modx->newObject(modSnippet::class);
        $this->snippet->fromArray([
            'name' => 'Unit Test Snippet',
            'description' => 'A snippet for unit testing.',
            'snippet' => str_replace('<?php','',file_get_contents(MODX_BASE_PATH.'_build/test/data/snippets/modSnippetTest/modSnippetTest.snippet.php')),
            'category' => 0,
            'locked' => false,
        ],'',true,true);
        $this->snippet->setProperties(['name' => 'John']);
        $this->snippet->setCacheable(false);
        $this->snippet->save();
        $this->modx->event= new modSystemEvent();
    }
    /**
     * Tear down fixtures after each test.
     *
     * @after
     */
    public function tearDownFixtures() {
        parent::tearDownFixtures();
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
     * Loading an unchanged static Snippet's content must not save the object.
     *
     * @return void
     */
    public function testGetUnchangedStaticContentDoesNotSetEditedon() {
        $staticFile = MODX_CORE_PATH . 'cache/unit-test-static-snippet.php';
        $snippet = $this->modx->newObject(modSnippet::class);
        $snippet->fromArray([
            'name' => 'Unit Test Static Snippet',
            'snippet' => 'return "Static content";',
            'static' => true,
            'static_file' => $staticFile,
        ]);

        try {
            $this->assertTrue($snippet->save());
            $this->assertEmpty($snippet->get('editedon'));

            /** @var modSnippet $reloaded */
            $reloaded = $this->modx->getObject(modSnippet::class, $snippet->get('id'), false);
            $this->assertSame('return "Static content";', $reloaded->getContent());
            $this->assertEmpty($reloaded->get('editedon'));
        } finally {
            $snippet->remove();
            @unlink($staticFile);
        }
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
        return [
            ['return "Goodbye.";'],
        ];
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
        return [
            ['Hello, John'],
            ['Hello, Mark', ['name' => 'Mark']],
        ];
    }
}

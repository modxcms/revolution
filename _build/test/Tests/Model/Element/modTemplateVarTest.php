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


use MODX\Revolution\modTemplateVar;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modTemplateVar class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Element
 * @group modElement
 * @group modTemplateVar
 */
class modTemplateVarTest extends MODxTestCase {
    /** @var modTemplateVar $tv */
    public $tv;

    /**
     * Setup fixtures before each test.
     *
     * @before
     */
    public function setUpFixtures() {
        parent::setUpFixtures();
        $this->tv = $this->modx->newObject(modTemplateVar::class);
        $this->tv->fromArray([
            'id' => 12345,
            'name' => 'Unit Test Template Var',
            'caption' => 'UTTV',
            'description' => 'A template variable for unit testing.',
            'default_text' => '<p>Hello, [[+name]]!</p>',
            'category' => 0,
            'locked' => 0,
        ],'',true,true);
        $this->tv->setProperties(['name' => 'John']);
        $this->tv->setCacheable(false);
    }
    /**
     * Tear down fixtures after each test.
     *
     * @after
     */
    public function tearDownFixtures() {
        parent::tearDownFixtures();
        $this->tv = null;
    }

    /**
     * @return void
     */
    public function testGetContent() {
        $this->assertEquals($this->tv->get('default_text'),$this->tv->getContent());
    }

    /**
     * @param string $content
     * @dataProvider providerSetContent
     * @depends testGetContent
     */
    public function testSetContent($content) {
        $this->tv->setContent($content);
        $this->assertEquals($content,$this->tv->get('default_text'));
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
        $result = $this->tv->process($properties,$content);
        $this->assertEquals($expected,$result);
    }
    /**
     * @return array
     */
    public function providerProcess() {
        return [
            ['<p>Hello, John!</p>'],
            ['<p>Hello, Mark!</p>', ['name' => 'Mark']],
            //array('<p>Having fun.</p>',array(),'<p>Having fun.</p>'),
            //array('<p>Test 1</p>',array('number' => 1),'<p>Test [[+number]]</p>'),
        ];
    }

    /**
     * Deprecated input types map to their fallbacks (#13077).
     */
    public function testDeprecatedInputTypesMap()
    {
        $types = modTemplateVar::getDeprecatedInputTypes();
        $this->assertSame('textarea', $types['textareamini']);
        $this->assertSame(['textareamini'], modTemplateVar::getDeprecatedInputTypeKeys());
    }

    /**
     * Deprecated and missing input types render as textarea without a fatal error.
     */
    public function testRenderInputFallsBackForDeprecatedAndMissingTypes()
    {
        $this->modx->controller = new class {
            public function setPlaceholder($k, $v)
            {
            }

            public function fetchTemplate($tpl)
            {
                return '<textarea id="tv-stub"></textarea>';
            }
        };

        $paths = $this->tv->getRenderDirectories('OnTVInputRenderList', 'input');

        $deprecated = $this->tv->getRender([], 'line one', $paths, 'input', 0, 'textareamini');
        $this->assertStringContainsString('textarea', strtolower($deprecated));

        $missing = $this->tv->getRender([], 'custom value', $paths, 'input', 0, 'removed-custom-tv-type');
        $this->assertStringContainsString('textarea', strtolower($missing));
    }

    /**
     * A registered render that returns an empty string must not be replaced by the fallback.
     */
    public function testRegisteredRenderEmptyOutputIsPreserved()
    {
        $this->tv->registerRenderMethod(
            'unit-test-empty-output',
            'output',
            modTemplateVarEmptyOutputRenderStub::class
        );

        $paths = $this->tv->getRenderDirectories('OnTVOutputRenderList', 'output');
        $output = $this->tv->getRender([], 'ignored', $paths, 'output', 0, 'unit-test-empty-output');
        $this->assertSame('', $output);
    }
}

/**
 * Stub output render used by modTemplateVarTest::testRegisteredRenderEmptyOutputIsPreserved.
 */
class modTemplateVarEmptyOutputRenderStub extends \MODX\Revolution\modTemplateVarOutputRender
{
    public function process($value, array $params = [])
    {
        return '';
    }
}

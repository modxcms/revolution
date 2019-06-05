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
namespace MODX\Revolution\Tests\Model\Sources;


use League\Flysystem\Filesystem;
use MODX\Revolution\modContext;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Sources\modFileMediaSource;

/**
 * Tests related to the modFileMediaSource class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Sources
 * @group modFileMediaSource
 */
class modFileMediaSourceTest extends MODxTestCase {
    /** @var modFileMediaSource $source */
    public $source;

    public function setUp() {
        parent::setUp();

        $this->source = $this->modx->newObject(modFileMediaSource::class);
        $this->source->fromArray(array(
            'name' => 'UnitTestFileSource',
            'description' => '',
            'class_key' => modFileMediaSource::class,
            'properties' => array(),
        ),'',true);
    }
    public function tearDown() {
        parent::tearDown();
        $this->source = null;
    }

    public function testInitialize() {
        $this->source->initialize();

        /** @var Filesystem $filesystem */
        $filesystem = $this->source->getFilesystem();

        /** @var modContext $context */
        $context = $this->source->getContext();

        $this->assertNotEmpty($filesystem);
        $this->assertInstanceOf('League\Flysystem\Filesystem', $filesystem);
        $this->assertNotEmpty($context);
        $this->assertInstanceOf(modContext::class, $context);
    }

    /**
     * Test getBases with no provided file and default settings
     */
    public function testGetBasesWithEmptyPath() {
        $this->source->initialize();
        $bases = $this->source->getBases('');

        $this->assertEquals('',$bases['path'],'Index "path" does not match expected.');
        $this->assertEquals(true,$bases['pathIsRelative'],'Index "pathIsRelative" does not match expected.');
        $this->assertEquals(MODX_BASE_PATH,$bases['pathAbsolute'],'Index "pathAbsolute" does not match expected.');
        $this->assertEquals(MODX_BASE_PATH,$bases['pathAbsoluteWithPath'],'Index "pathAbsoluteFull" does not match expected.');
        $this->assertEquals('',$bases['pathRelative'],'Index "pathRelative" does not match expected.');

        $this->assertEquals('',$bases['url'],'Index "url" does not match expected.');
        $this->assertEquals(true,$bases['urlIsRelative'],'Index "urlIsRelative" does not match expected.');
        $this->assertEquals('/',$bases['urlAbsolute'],'Index "urlAbsolute" does not match expected.');
        $this->assertEquals('/',$bases['urlAbsoluteWithPath'],'Index "urlAbsoluteWithPath" does not match expected.');
        $this->assertEquals('',$bases['urlRelative'],'Index "urlRelative" does not match expected.');
    }

    /**
     * Test getBases with a provided file and default settings
     */
    public function testGetBasesWithProvidedFile() {
        $this->source->initialize();
        $bases = $this->source->getBases('assets/images/logo.png');

        $this->assertEquals('',$bases['path'],'Index "path" does not match expected.');
        $this->assertEquals(true,$bases['pathIsRelative'],'Index "pathIsRelative" does not match expected.');
        $this->assertEquals(MODX_BASE_PATH,$bases['pathAbsolute'],'Index "pathAbsolute" does not match expected.');
        $this->assertEquals(MODX_BASE_PATH.'assets/images/logo.png',$bases['pathAbsoluteWithPath'],'Index "pathAbsoluteFull" does not match expected.');
        $this->assertEquals('assets/images/logo.png',$bases['pathRelative'],'Index "pathRelative" does not match expected.');

        $this->assertEquals('',$bases['url'],'Index "url" does not match expected.');
        $this->assertEquals(true,$bases['urlIsRelative'],'Index "urlIsRelative" does not match expected.');
        $this->assertEquals('/',$bases['urlAbsolute'],'Index "urlAbsolute" does not match expected.');
        $this->assertEquals('/assets/images/logo.png',$bases['urlAbsoluteWithPath'],'Index "urlAbsoluteWithPath" does not match expected.');
        $this->assertEquals('assets/images/logo.png',$bases['urlRelative'],'Index "urlRelative" does not match expected.');
    }
}

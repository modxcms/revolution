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

    /**
     * @return void
     */
    public function setUp() {
        parent::setUp();

        $this->modx->loadClass('sources.modMediaSource');
        $this->modx->loadClass('sources.modFileMediaSource');
        $this->source = $this->modx->newObject('sources.modFileMediaSource');
        $this->source->fromArray(array(
            'name' => 'UnitTestFileSource',
            'description' => '',
            'class_key' => 'sources.modFileMediaSource',
            'properties' => array(),
        ),'',true);
    }
    public function tearDown() {
        parent::tearDown();
        $this->source = null;
    }
    
    public function testInitialize() {
        $this->source->initialize();
        $this->assertNotEmpty($this->source->fileHandler);
        $this->assertInstanceOf('modFileHandler',$this->source->fileHandler);
        $this->assertNotEmpty($this->source->ctx);
        $this->assertInstanceOf('modContext',$this->source->ctx);
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

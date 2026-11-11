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

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Sources\modFileMediaSource;
use MODX\Revolution\Sources\modMediaSource;
use ReflectionMethod;

/**
 * Tests related to the modMediaSource class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Sources
 * @group modMediaSource
 */
class modMediaSourceTest extends MODxTestCase
{
    /** @var modMediaSource $source */
    public $source;

    /**
     * Setup fixtures before each test.
     *
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();

        $this->source = $this->modx->newObject(modFileMediaSource::class);
        $this->source->fromArray([
            'name' => 'UnitTestSource',
            'description' => '',
            'class_key' => modFileMediaSource::class,
            'properties' => [],
        ], '', true);
    }
    /**
     * Tear down fixtures after each test.
     *
     * @after
     */
    public function tearDownFixtures()
    {
        parent::tearDownFixtures();
        $this->source = null;
    }

    /**
     * Flysystem object paths must stay on forward slashes, even when the OS uses "\".
     */
    public function testSanitizePathUsesForwardSlash()
    {
        $this->assertSame('folder/sub', $this->source->sanitizePath('folder\\sub'));
        $this->assertSame('folder/sub', $this->source->sanitizePath('folder//sub'));
        $this->assertSame('folder/sub/', $this->source->postfixSlash('folder\\sub'));
        $this->assertSame('/', $this->source->postfixSlash(''));
    }

    /**
     * Renaming a nested path must keep the parent directory (#15438 Windows regression).
     */
    public function testGetRenamedPathKeepsParentWithMixedSeparators()
    {
        $method = new ReflectionMethod(modMediaSource::class, 'getRenamedPath');

        $this->assertSame('parent/new-name', $method->invoke($this->source, 'parent/old-name', 'new-name'));
        $this->assertSame('parent/new-name', $method->invoke($this->source, 'parent\\old-name', 'new-name'));
        $this->assertSame('a/b/renamed', $method->invoke($this->source, 'a/b/c', 'renamed'));
        $this->assertSame('renamed', $method->invoke($this->source, 'old-name', 'renamed'));
    }

    /**
     * Root detection must treat "/" as root after postfixSlash (Windows DIRECTORY_SEPARATOR is "\").
     */
    public function testIsFilesystemRootPathAfterPostfix()
    {
        $method = new ReflectionMethod(modMediaSource::class, 'isFilesystemRootPath');

        $this->assertTrue($method->invoke($this->source, '/'));
        $this->assertTrue($method->invoke($this->source, '\\'));
        $this->assertTrue($method->invoke($this->source, ''));
        $this->assertFalse($method->invoke($this->source, 'assets/'));
    }

    /**
     * Move/create path joins must use "/" so Windows hosts match Flysystem keys.
     */
    public function testJoinFilesystemPathsUsesForwardSlash()
    {
        $method = new ReflectionMethod(modMediaSource::class, 'joinFilesystemPaths');

        $this->assertSame('folder/sub/file.txt', $method->invoke($this->source, 'folder/sub/', 'file.txt'));
        $this->assertSame('folder/sub/file.txt', $method->invoke($this->source, 'folder\\sub\\', 'file.txt'));
        $this->assertSame('file.txt', $method->invoke($this->source, '/', 'file.txt'));
    }
}

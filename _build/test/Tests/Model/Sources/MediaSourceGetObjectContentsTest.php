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
use League\Flysystem\UnableToRetrieveMetadata;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Sources\modFileMediaSource;
use ReflectionProperty;

/**
 * Regression for #16497: content must survive metadata retrieval failures.
 *
 * @group Model
 * @group Sources
 * @group modMediaSource
 */
class MediaSourceGetObjectContentsTest extends MODxTestCase
{
    public function testGetObjectContentsKeepsBodyWhenMimeTypeFails()
    {
        /** @var modFileMediaSource $source */
        $source = $this->modx->newObject(modFileMediaSource::class);
        $source->fromArray([
            'name' => 'UnitTestGetObjectContents',
            'class_key' => modFileMediaSource::class,
            'properties' => [],
        ], '', true);
        $this->assertTrue((bool)$source->save());

        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method('fileExists')->willReturn(true);
        $filesystem->method('read')->willReturn('payload-from-s3');
        $filesystem->method('fileSize')->willReturn(15);
        $filesystem->method('lastModified')->willReturn(1700000000);
        $filesystem->method('mimeType')->willThrowException(
            UnableToRetrieveMetadata::mimeType('demo.jpg')
        );

        $prop = new ReflectionProperty(modFileMediaSource::class, 'filesystem');
        $prop->setAccessible(true);
        $prop->setValue($source, $filesystem);

        try {
            $result = $source->getObjectContents('demo.jpg');
            $this->assertNotEmpty($result);
            $this->assertSame('payload-from-s3', $result['content']);
            $this->assertSame('application/octet-stream', $result['mime']);
        } finally {
            $source->remove();
        }
    }
}

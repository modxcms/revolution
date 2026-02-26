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
namespace MODX\Revolution\Tests\Model\Transport;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Transport\modTransportPackage;
use ReflectionMethod;

/**
 * Stream wrapper for testing getStreamOrFileSize with Content-Length header.
 * PHP stream wrapper interface requires exact method names: stream_open, stream_read, etc.
 */
class ModTransportPackageTestStreamWrapper
{
    public $context;
    public $wrapper_data;
    private $position = 0;
    private $content = '';

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- PHP stream wrapper requires exact method name
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $this->content = 'test';
        $this->position = 0;
        $this->wrapper_data = ['Content-Length: 12345'];
        return true;
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- PHP stream wrapper requires exact method name
    public function stream_stat()
    {
        return [];
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- PHP stream wrapper requires exact method name
    public function stream_read($count)
    {
        $ret = substr($this->content, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- PHP stream wrapper requires exact method name
    public function stream_eof()
    {
        return $this->position >= strlen($this->content);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- PHP stream wrapper requires exact method name
    public function stream_seek($offset, $whence = SEEK_SET)
    {
        return true;
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- PHP stream wrapper requires exact method name
    public function stream_tell()
    {
        return $this->position;
    }
}

/**
 * Tests related to the modTransportPackage class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Transport
 * @group modTransportPackage
 */
class modTransportPackageTest extends MODxTestCase
{
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * Test getReadByteLimit returns positive integer (half of memory_limit).
     */
    public function testGetReadByteLimit()
    {
        $pkg = $this->modx->newObject(modTransportPackage::class);
        $method = new ReflectionMethod(modTransportPackage::class, 'getReadByteLimit');
        $method->setAccessible(true);

        $limit = $method->invoke($pkg);
        $this->assertIsInt($limit);
        $this->assertGreaterThan(0, $limit);
    }

    /**
     * Test getStreamOrFileSize for local file path returns filesize.
     */
    public function testGetStreamOrFileSizeLocalFile()
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'modx_test_');
        $content = 'test content for filesize';
        file_put_contents($tmpFile, $content);
        $expectedSize = strlen($content);

        $pkg = $this->modx->newObject(modTransportPackage::class);
        $method = new ReflectionMethod(modTransportPackage::class, 'getStreamOrFileSize');
        $method->setAccessible(true);

        $handle = fopen($tmpFile, 'rb');
        $size = $method->invoke($pkg, $tmpFile, $handle);
        fclose($handle);
        unlink($tmpFile);

        $this->assertSame($expectedSize, $size);
    }

    /**
     * Test getStreamOrFileSize for URL source with empty wrapper_data returns null.
     */
    public function testGetStreamOrFileSizeUrlEmptyWrapperData()
    {
        $pkg = $this->modx->newObject(modTransportPackage::class);
        $method = new ReflectionMethod(modTransportPackage::class, 'getStreamOrFileSize');
        $method->setAccessible(true);

        $handle = fopen('php://temp', 'rb');
        $size = $method->invoke($pkg, 'http://example.com/file.zip', $handle);
        fclose($handle);

        $this->assertNull($size);
    }

    /**
     * Test getStreamOrFileSize for URL source with Content-Length in wrapper_data.
     */
    public function testGetStreamOrFileSizeUrlWithContentLength()
    {
        if (in_array('modxtest', stream_get_wrappers(), true)) {
            stream_wrapper_unregister('modxtest');
        }
        stream_wrapper_register('modxtest', ModTransportPackageTestStreamWrapper::class);

        $pkg = $this->modx->newObject(modTransportPackage::class);
        $method = new ReflectionMethod(modTransportPackage::class, 'getStreamOrFileSize');
        $method->setAccessible(true);

        $handle = fopen('modxtest://file.zip', 'rb');
        $size = $method->invoke($pkg, 'http://example.com/file.zip', $handle);
        fclose($handle);
        stream_wrapper_unregister('modxtest');

        $this->assertSame(12345, $size);
    }
}

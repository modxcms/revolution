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

namespace MODX\Revolution\Tests\Processors\Browser;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Browser\File\Remove;

/**
 * Regression for #16663: Browser::sanitize must keep double dots inside filenames.
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group BrowserProcessors
 */
class BrowserSanitizeTest extends MODxTestCase
{
    /** @var Remove */
    private $processor;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->processor = new Remove($this->modx);
    }

    /**
     * @dataProvider providerSanitize
     * @param string $input
     * @param string $expected
     */
    public function testSanitizePreservesFilenameDotsAndTrailingSlash($input, $expected)
    {
        $this->assertSame($expected, $this->processor->sanitize($input));
    }

    public function providerSanitize()
    {
        return [
            'double dots in filename' => ['somefile..txt', 'somefile..txt'],
            'directory plus double-dot file' => ['folder/somefile..txt', 'folder/somefile..txt'],
            'preserves trailing slash for File/Create' => ['folder/', 'folder/'],
            'collapses duplicate slashes' => ['folder//file.txt', 'folder/file.txt'],
            'hidden file leading dot' => ['.htaccess', '.htaccess'],
            'url-encoded filename' => ['some%20file..txt', 'some file..txt'],
            'leaves relative parent segment for Flysystem' => ['../escape', '../escape'],
            'leaves nested parent segment for Flysystem' => ['a/../b', 'a/../b'],
        ];
    }
}

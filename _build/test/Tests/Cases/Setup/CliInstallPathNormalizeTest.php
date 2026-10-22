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

namespace MODX\Revolution\Tests\Cases\Setup;

use MODX\Setup\modInstallPathUtil;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * @package modx-test
 * @subpackage setup
 * @group Cases
 * @group Setup
 */
class CliInstallPathNormalizeTest extends XTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        require_once dirname(__DIR__, 5) . '/setup/includes/modinstallpathutil.class.php';
    }

    /**
     * @param string $input
     * @param string $expected
     * @dataProvider providerNormalizePathOrUrl
     */
    public function testNormalizePathOrUrl($input, $expected)
    {
        $this->assertSame($expected, modInstallPathUtil::normalizePathOrUrl($input));
    }

    /**
     * @return array
     */
    public function providerNormalizePathOrUrl()
    {
        return [
            ['D:/laragon/www/site/core', 'D:/laragon/www/site/core/'],
            ['D:\\laragon\\www\\site\\core', 'D:/laragon/www/site/core/'],
            ['\\\\server\\share\\site\\core', '//server/share/site/core/'],
            ['//server/share/site/core', '//server/share/site/core/'],
            ['/var/www/site/core', '/var/www/site/core/'],
            ['var/www/site/core', '/var/www/site/core/'],
            ['/manager/', '/manager/'],
        ];
    }
}

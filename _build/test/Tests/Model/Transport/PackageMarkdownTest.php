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
use MODX\Revolution\Transport\PackageMarkdown;

/**
 * Tests for PackageMarkdown helper used by package manager details.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Transport
 * @group PackageMarkdown
 */
class PackageMarkdownTest extends MODxTestCase
{
    public function testParseRendersMarkdownLinksAndHeadings(): void
    {
        $html = PackageMarkdown::parse(
            "[Omise](https://www.omise.co) is a gateway.\n\n## Setup\n\nEnable the module."
        );

        $this->assertStringContainsString('<a href="https://www.omise.co">Omise</a>', $html);
        $this->assertStringContainsString('<h2>Setup</h2>', $html);
        $this->assertStringContainsString('<p>Enable the module.</p>', $html);
    }

    public function testParseFieldsOnlyTouchesKnownStringFields(): void
    {
        $parsed = PackageMarkdown::parseFields([
            'name' => 'Omise Payment Gateway for Commerce',
            'description' => 'See [Omise](https://www.omise.co).',
            'instructions' => "## Setup\n\nDo this.",
            'changelog' => '- Added 3D Secure',
            'downloads' => 412,
            'signature' => 'commerce_omise-1.1.0-pl',
        ]);

        $this->assertSame('Omise Payment Gateway for Commerce', $parsed['name']);
        $this->assertSame(412, $parsed['downloads']);
        $this->assertSame('commerce_omise-1.1.0-pl', $parsed['signature']);
        $this->assertStringContainsString('<a href="https://www.omise.co">Omise</a>', $parsed['description']);
        $this->assertStringContainsString('<h2>Setup</h2>', $parsed['instructions']);
        $this->assertStringContainsString('<li>Added 3D Secure</li>', $parsed['changelog']);
    }

    public function testProviderFieldsLeaveLicensePlain(): void
    {
        $parsed = PackageMarkdown::parseFields([
            'description' => 'See [Omise](https://www.omise.co).',
            'license' => 'GPLv2',
        ], PackageMarkdown::PROVIDER_FIELDS);

        $this->assertStringContainsString('<a href="https://www.omise.co">Omise</a>', $parsed['description']);
        $this->assertSame('GPLv2', $parsed['license']);
    }

    public function testSafeModeEscapesRawHtml(): void
    {
        $html = PackageMarkdown::parse('Hello <script>alert(1)</script>');
        $this->assertStringNotContainsString('<script>', $html);
    }
}

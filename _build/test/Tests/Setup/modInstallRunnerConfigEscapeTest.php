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
namespace MODX\Revolution\Tests\Setup;

use MODX\Revolution\MODxTestCase;

/**
 * Tests for modInstallRunner::escapeConfigValue().
 *
 * Values collected during setup are substituted into single-quoted PHP strings in
 * config.inc.tpl, so each value must be escaped for that context. These tests verify
 * that escaped values embed correctly and round-trip back to the original, including
 * values that contain quotes, backslashes, and other syntax characters.
 *
 * @package modx-test
 * @subpackage setup
 * @group Setup
 */
class modInstallRunnerConfigEscapeTest extends MODxTestCase
{
    /**
     * The runner lives in the global namespace and is loaded via require, not autoload.
     *
     * @before
     */
    public function loadRunnerClass()
    {
        require_once dirname(__DIR__, 4) . '/setup/includes/runner/modinstallrunner.class.php';
    }

    /**
     * Values are escaped exactly as needed for a single-quoted PHP string.
     *
     * @dataProvider providerExactEscape
     * @param string $input    The raw value.
     * @param string $expected The escaped value.
     */
    public function testEscapesForSingleQuotedContext($input, $expected)
    {
        $this->assertSame($expected, \modInstallRunner::escapeConfigValue($input));
    }

    public function providerExactEscape()
    {
        return [
            'clean value untouched' => ['modx_', 'modx_'],
            'single quote escaped' => ["a'b", "a\\'b"],
            'backslash escaped' => ["a\\b", "a\\\\b"],
            'double quotes preserved' => ['say "hi"', 'say "hi"'],
        ];
    }

    /**
     * An escaped value embedded in a single-quoted PHP string must evaluate back to
     * exactly the original input, whatever characters the value contains.
     *
     * @dataProvider providerRoundTrip
     * @param string $input The raw value to round-trip through a single-quoted string.
     */
    public function testRoundTripsThroughSingleQuotedString($input)
    {
        eval('$actual = \'' . \modInstallRunner::escapeConfigValue($input) . '\';');
        $this->assertSame($input, $actual);
    }

    public function providerRoundTrip()
    {
        return [
            'plain prefix' => ['modx_'],
            'quotes and syntax characters' => ["O'Reilly; \$total = 100 * [qty];"],
            'embedded single quote' => ["O'Brien"],
            'backslash' => ['back\\slash'],
            'trailing backslash' => ['trailing\\'],
            'quote and backslash' => ["mix'\\end"],
            'newline' => ["line1\nline2"],
            'double quotes preserved' => ['say "hi" now'],
        ];
    }
}

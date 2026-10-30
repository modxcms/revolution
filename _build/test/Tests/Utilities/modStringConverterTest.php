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

/** @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps */

namespace MODX\Revolution\Tests\Utilities\Converters;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Utilities\Converters\modStringConverter;

/**
 * Tests related to string conversion utilities
 *
 * @package modx-test
 * @subpackage modx
 * @group Utilities
 */
class modStringConverterTest extends MODxTestCase
{
    /** @var modStringConverter $converters */
    public $converters;

    /**
     * Setup fixtures before each test.
     *
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->converters = $this->modx->services->get(modStringConverter::class);
    }

    /**
     * Basic tests for htmlToJSON, mainly to ensure that whitespace is handled correctly
     */
    public function testHtmlToJSON()
    {
        $reflection = new \ReflectionClass($this->converters);
        $htmlToJsonOpts = $reflection->getConstant('HTML_TO_JSON_OPTS');

        // Test 1
        $result = $this->converters->htmlToJSON('    ');
        $this->assertEquals('', $result, 'htmlToJSON should return empty string for whitespace-only input');

        // Test 2
        $pTagOpen = trim(json_encode('<p>', $htmlToJsonOpts), '"');
        $pTagClose = trim(json_encode('</p>', $htmlToJsonOpts), '"');
        $result = $this->converters->htmlToJSON('<p>content</p>   <p>more content</p>');
        $this->assertEquals(
            '"' . $pTagOpen . 'content' . $pTagClose . ' ' . $pTagOpen . 'more content' . $pTagClose . '"',
            $result,
            'htmlToJSON should collapse spaces between tags to a single space'
        );

        // Test 3a
        $bracketLeft = trim(json_encode('<', $htmlToJsonOpts), '"');
        $bracketRight = trim(json_encode('>', $htmlToJsonOpts), '"');
        $result = $this->converters->htmlToJSON('<p>content >    < more content</p>');
        $this->assertEquals(
            '"' . $pTagOpen . 'content ' . $bracketRight . ' ' . $bracketLeft . ' more content' . $pTagClose . '"',
            $result,
            'htmlToJSON should retain facing angle brackets within content nodes and retain a single space between when there is more than one space to begin with'
        );

        // Test 3b
        $result = $this->converters->htmlToJSON('<p>content >< more content</p>');
        $this->assertEquals(
            '"' . $pTagOpen . 'content ' . $bracketRight . $bracketLeft . ' more content' . $pTagClose . '"',
            $result,
            'htmlToJSON should retain facing angle brackets within content nodes and not add space between when there is none to begin with'
        );

        // Test 4
        $multilineTest = <<<TEST
            <p>content</p>
                <p>more content</p>
        TEST;
        $result = $this->converters->htmlToJSON($multilineTest);
        $this->assertEquals(
            '"' . $pTagOpen . 'content' . $pTagClose . ' ' . $pTagOpen . 'more content' . $pTagClose . '"',
            $result,
            'htmlToJSON should remove newlines, tabs, and multiple spaces between tags and replace with a single space'
        );
    }
}

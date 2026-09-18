<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps */

namespace MODX\Revolution\Utilities\Converters;

use MODX\Revolution\modX;

/**
 * Provides utility methods to processors and controllers for converting text content from one format to another
 *
 * @package MODX\Revolution
 */
class modStringConverter
{
    /**
     * A reference to the modX object.
     * @var modX $modx
     */
    protected ?modX $modx = null;

    private const HTML_TO_JSON_OPTS = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES;

    public function __construct(modX $modx)
    {
        $this->modx =& $modx;
    }

    /**
     * Converts an html-formatted string to JSON, configured such that it can be
     * reliably decoded and consumed as a value in a javascript config object
     *
     * @param string $string The unencoded html string
     * @return string The JSON-encoded string
     */
    public function htmlToJSON(string $string): string
    {
        $string = trim($string);
        if (empty($string)) {
            return '';
        }

        /*
            Collapse presentational (code formatting) space; retain single space
            in replacement to account for the rare instance where the content
            contains facing angle brackets, e.g., "some text > < some more text"
            which would otherwise be collapsed to "some text >< some more text"

            Given the stated end-use of this method's output, it's very important for
            line breaks to be removed as they cause parsing failures when present in
            javascript object property values
        */
        $regex = '/(?<=>)[\s]+(?=<)/';

        return json_encode(
            preg_replace($regex, ' ', $string),
            self::HTML_TO_JSON_OPTS
        );
    }
}

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

namespace MODX\Revolution\Utilities\Sanitizers;

use MODX\Revolution\modX;

/**
 * Provides utility methods to processors and controllers for sanitizing text content
 *
 * @package MODX\Revolution
 */
class modStringSanitizer
{
    /**
     * A reference to the modX object.
     * @var modX $modx
     */
    protected ?modX $modx = null;

    public function __construct(modX $modx)
    {
        $this->modx =& $modx;
    }

    /**
     * Removes unwanted tags and/or tag attributes from an HTML string.
     * Assumes well-designed HTML without inline php (which is not supported
     * and always stripped).
     *
     * @param string $htmlSource The html string to clean
     * @param ?string|array $allowedTags An array or comma-separated list of tag names to allow
     * @param ?string|array $allowedAttr An array or comma-separated list of tag attribute names to allow.
     * Note that data-[xyz] attributes may be allowed by adding "data" to the list.
     * @param bool $allowScripts Whether to allow javascript in html source passed to this method
     * @param bool $allowComments Whether to allow comments in the final output
     */
    public function stripHTML(
        string $htmlSource,
        string|array|null $allowedTags = '',
        string|array|null $allowedAttr = '',
        bool $allowScripts = false,
        bool $allowComments = false
    ): string {
        if (trim($htmlSource) === '') {
            return '';
        }

        $allowedTags = is_string($allowedTags) ? trim($allowedTags) : $allowedTags ;

        if (!is_array($allowedTags)) {
            $allowedTags = preg_replace('/[\s<>]+/', '', $allowedTags);
            $allowedTags = explode(',', $allowedTags);
        } else {
            $allowedTags = array_map(function ($tag) {
                return preg_replace('/[\s<>]+/', '', $tag);
            }, $allowedTags);
        }

        // Strip php before encoding
        $htmlSource = preg_replace('/<\s*?\?(?:php)?[\s\S]*?\?\s*?>|<\s*?\?(?:php)?[\s\S]*/m', '', $htmlSource);

        if (!empty($allowedAttr)) {
            if (!is_array($allowedAttr)) {
                $allowedAttr = explode(',', $allowedAttr);
            }
            $allowedAttr = array_map('trim', $allowedAttr);
        } else {
            $allowedAttr = [];
        }

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        // Prevent additional formatting of the source string
        $dom->formatOutput = false;

        /*
            To support multiple languages, the source needs to be mb-encoded to
            ensure DOMDocument can cleanly parse the string and avoid errors.
        */
        $content = mb_encode_numericentity(
            $htmlSource,
            [0x80, 0x10FFFF, 0, ~0],
            'UTF-8'
        );

        /*
            Need a placeholder wrapping tag, as loadHTML will automatically
            wrap strings with no root tag with a <p> tag (do not want that)
        */
        $loaded = $dom->loadHTML(
            '<phwrap>' . $content . '</phwrap>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        if (!$loaded) {
            $errors = libxml_get_errors();
            $msg = 'stripHTML — DOMDocument::loadHTML failed with the following errors: ' . print_r($errors, true);
            $this->modx->log(modX::LOG_LEVEL_ERROR, "\r\n$msg");
            libxml_clear_errors();
            return '';
        }

        $xpath = new \DOMXPath($dom);

        if (!$allowComments) {
            foreach ($xpath->query("//comment()") as $node) {
                $node->parentNode->removeChild($node);
            }
        }

        /*
            Before the main query, remove nodes where not only the tag,
            but its contents should be removed. Otherwise the parse will
            in most cases not be able to identify the content to remove
            because its enclosing tag had already been removed.
        */
        if (!$allowScripts || !in_array('script', $allowedTags)) {
            foreach ($xpath->query("//script") as $node) {
                $node->parentNode->removeChild($node);
            }
        }

        // Main query
        foreach ($xpath->query("//*") as $node) {
            $parent = $node->parentNode;
            if (in_array($node->nodeName, $allowedTags)) {
                if ($node->attributes->length > 0) {
                    if (empty($allowedAttr)) {
                        for ($i = 0; $i < $node->attributes->length; $i++) {
                            /** @disregard P1013 */
                            $node->removeAttribute($node->attributes->item(0)->nodeName);
                        }
                    } else {
                        $nodeAttrRemove = [];
                        for ($i = 0; $i < $node->attributes->length; $i++) {
                            $name = $node->attributes->item($i)->nodeName;
                            /*
                                Because data attributes are infinitely variable, but always begin with 'data-', allowing this attribute is done by simply entering
                                'data' in the allowed list. Special handling for that done here.
                            */
                            $attrIsAllowed = in_array($name, $allowedAttr) || (in_array('data', $allowedAttr) && strpos($name, 'data-') === 0);
                            if (!$allowScripts && strpos($name, 'on') === 0) {
                                // Event handlers are the only attributes beginning with 'on'
                                $nodeAttrRemove[] = $name;
                                continue;
                            }
                            if ($attrIsAllowed) {
                                if (!$allowScripts) {
                                    // Javascript shouldn't be able to run in other attributes, but just in case replace it with a placeholder when scripts are disallowed
                                    $testVal = preg_replace('/[^a-zA-Z:]+/', '', $node->attributes->item($i)->nodeValue);
                                    if (stripos($testVal, 'javascript:') !== false) {
                                        $node->attributes->item($i)->nodeValue = '#js-not-allowed#';
                                    }
                                }
                            } else {
                                $nodeAttrRemove[] = $name;
                            }
                        }
                        foreach ($nodeAttrRemove as $attr) {
                            /** @disregard P1013 */
                            $node->removeAttribute($attr);
                        }
                    }
                }
            } else {
                while ($node->hasChildNodes()) {
                    $parent->insertBefore($node->lastChild, $node->nextSibling);
                }
                $parent->removeChild($node);
            }
        }
        // Account for cases where libxml adds a trailing newline
        $output = rtrim($dom->saveHTML(), "\n");

        // The loop above should already have dropped this placeholder tag, but just in case it did not...
        if (strpos($output, '<phwrap>') !== false) {
            $output = str_replace(['<phwrap>', '</phwrap>'], '', $output);
        }

        return $output;
    }
}

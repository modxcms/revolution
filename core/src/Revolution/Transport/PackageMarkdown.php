<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Transport;

use Parsedown;

/**
 * Parses package text attributes that may contain Markdown (provider + local).
 */
class PackageMarkdown
{
    /** Full transport attributes (GetAttribute / install meta tabs). */
    public const FIELDS = [
        'changelog',
        'description',
        'instructions',
        'license',
        'readme',
    ];

    /**
     * Provider list/details payload fields rendered as HTML bodies.
     * Excludes short meta values like license shown as plain text in the aside.
     */
    public const PROVIDER_FIELDS = [
        'changelog',
        'description',
        'instructions',
    ];

    /**
     * @param string $text Raw markdown or plain text
     * @return string Safe HTML
     */
    public static function parse(string $text): string
    {
        $parser = new Parsedown();
        $parser->setSafeMode(true);

        return $parser->text($text);
    }

    /**
     * Parse markdown fields present on a package payload.
     *
     * @param array $data Package row / attributes
     * @param array $fields Field names to parse
     * @return array
     */
    public static function parseFields(array $data, array $fields = self::FIELDS): array
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }
            $value = $data[$field];
            if ($value !== null && !is_string($value)) {
                continue;
            }
            $data[$field] = self::parse($value ?? '');
        }

        return $data;
    }
}

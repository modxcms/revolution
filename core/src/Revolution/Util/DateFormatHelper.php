<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Util;

/**
 * Replaces deprecated strftime() for PHP 8.1+.
 * Uses IntlDateFormatter when ext-intl is available, otherwise date() with format conversion.
 */
class DateFormatHelper
{
    /**
     * Format a timestamp using strftime-style format string.
     *
     * @param string   $strftimeFormat Format string (strftime tokens: %Y, %m, %d, etc.)
     * @param int      $timestamp      Unix timestamp
     * @param string|null $locale      Locale for IntlDateFormatter (e.g. 'en_US'). Default null uses PHP default.
     * @return string Formatted date string
     */
    public static function format($strftimeFormat, $timestamp, $locale = null)
    {
        if (extension_loaded('intl') && class_exists('IntlDateFormatter')) {
            $icuPattern = self::strftimeToIcuPattern($strftimeFormat);
            $locale = $locale ?: 'en_US';
            $formatter = new \IntlDateFormatter(
                $locale,
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::FULL,
                null,
                null,
                $icuPattern
            );
            $result = $formatter->format($timestamp);
            if ($result !== false) {
                return $result;
            }
        }
        $dateFormat = self::strftimeToDateFormat($strftimeFormat);
        return date($dateFormat, $timestamp);
    }

    /**
     * Convert strftime format to PHP date() format.
     *
     * @param string $strftimeFormat
     * @return string
     */
    public static function strftimeToDateFormat($strftimeFormat)
    {
        $map = [
            '%Y' => 'Y',
            '%y' => 'y',
            '%m' => 'm',
            '%d' => 'd',
            '%H' => 'H',
            '%I' => 'h',
            '%M' => 'i',
            '%S' => 's',
            '%p' => 'A',
            '%P' => 'a',
            '%T' => 'H:i:s',
            '%F' => 'Y-m-d',
            '%e' => 'j',
            '%A' => 'l',
            '%a' => 'D',
            '%B' => 'F',
            '%b' => 'M',
            '%h' => 'M',
            '%x' => 'm/d/Y',
            '%X' => 'H:i:s',
            '%c' => 'D M j H:i:s Y',
            '%r' => 'h:i:s A',
            '%R' => 'H:i',
            '%D' => 'm/d/y',
            '%l' => 'g',
            '%n' => "\n",
            '%t' => "\t",
            '%%' => '%',
        ];
        return str_replace(array_keys($map), array_values($map), $strftimeFormat);
    }

    /**
     * Convert strftime format to ICU date pattern for IntlDateFormatter.
     *
     * @param string $strftimeFormat
     * @return string ICU pattern
     */
    private static function strftimeToIcuPattern($strftimeFormat)
    {
        $map = [
            '%Y' => 'yyyy',
            '%y' => 'yy',
            '%m' => 'MM',
            '%d' => 'dd',
            '%H' => 'HH',
            '%I' => 'hh',
            '%M' => 'mm',
            '%S' => 'ss',
            '%p' => 'a',
            '%P' => 'a',
            '%T' => 'HH:mm:ss',
            '%F' => 'yyyy-MM-dd',
            '%e' => 'd',
            '%A' => 'EEEE',
            '%a' => 'EEE',
            '%B' => 'MMMM',
            '%b' => 'MMM',
            '%h' => 'MMM',
            '%x' => 'MM/dd/yyyy',
            '%X' => 'HH:mm:ss',
            '%c' => 'EEE MMM d HH:mm:ss yyyy',
            '%r' => 'hh:mm:ss a',
            '%R' => 'HH:mm',
            '%D' => 'MM/dd/yy',
            '%l' => 'h',
            '%n' => "\n",
            '%t' => "\t",
            '%%' => "'%'",
        ];
        $icu = str_replace(array_keys($map), array_values($map), $strftimeFormat);
        return $icu;
    }
}

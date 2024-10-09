<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Formatter;

use MODX\Revolution\modX;

/**
 * Provides utility methods for processors and controllers
 *
 * @package MODX\Revolution
 */
class modManagerDateFormatter
{
    /**
     * A reference to the modX object.
     * @var modX $modx
     */
    protected ?modX $modx = null;

    // DATE FORMATTER PROPERTIES

    /**
     * @var string $managerDateFormat The php datetime format for dates, as defined in the system settings
     */
    protected string $managerDateFormat = '';

    /**
     * @var string $managerTimeFormat The php datetime format for times, as defined in the system settings
     */
    protected string $managerTimeFormat = '';

    /**
     * @var string $managerDateHiddenFormat Standard mysql format required for date transformations
     */
    protected string $managerDateHiddenFormat = 'Y-m-d H:i:s';

    /**
     * @var array $managerDateEmptyValues A list of possible values representing an empty date
     */
    protected array $managerDateEmptyValues = [
        '',
        '-001-11-30 00:00:00',
        '-1-11-30 00:00:00',
        '1969-12-31 00:00:00',
        '0000-00-00 00:00:00',
        0,
        '0',
        null
    ];

    /**
     * @var string $managerDateEmptyDisplay The text (if any) to show for empty dates
     */
    private string $managerDateEmptyDisplay = '';


    public function __construct(modX $modx)
    {
        $this->modx =& $modx;
        $this->managerDateFormat = $this->modx->getOption('manager_date_format', null, 'Y-m-d', true);
        $this->managerTimeFormat = $this->modx->getOption('manager_time_format', null, 'H:i', true);
        $this->managerDateEmptyDisplay = $this->modx->getOption('manager_datetime_empty_value', null, '–', true);
    }

    public function isEmpty($value): bool
    {
        return in_array($value, $this->managerDateEmptyValues);
    }

    /**
     * Calculates the Unix timestamp for a valid date/time-related value,
     * applying the system-specified server offset if applicable
     * @param string|int $value The value to transform (a Unix timestamp or mysql-format string)
     * @param bool $useOffset Whether to use the offset time (system setting) in the date calculation
     * @return int|null The calculated timestamp
     */
    protected function parseValue($value, bool $useOffset = false): ?int
    {
        if ($this->isEmpty($value)) {
            return null;
        }

        if (!modX::isValidTimestamp($value)) {
            // If not a timestamp integer, expecting mysql-formatted value
            $dateTime = \DateTimeImmutable::createFromFormat($this->managerDateHiddenFormat, $value);
            if ($dateTime === false) {
                $msg = $this->modx->lexicon('datetime_conversion_err_invalid_mysql_format', ['value' => $value]);
                $this->modx->log(modX::LOG_LEVEL_WARN, $msg);
                return 0;
            }
            $value = $dateTime->getTimestamp();
        }

        $value = (int)$value;

        if (!$useOffset) {
            return $value;
        }

        $offset = floatval($this->modx->getOption('server_offset_time', null, 0)) * 3600;

        return $value + $offset;
    }

    /**
     * Transforms a date/time-related value using the specified DateTime format
     * @param string|int $value The value to transform (a Unix timestamp or mysql-format string)
     * @param string $format The custom format to use when formatting the $value
     * @param bool $useOffset Whether to use the offset time (system setting) in the date calculation
     * @param string|null $emptyValue The text to show when the $value passed is empty
     * @return string The formatted date
     */
    public function format($value, string $format, bool $useOffset = false, ?string $emptyValue = null): string
    {
        $value = $this->parseValue($value, $useOffset);

        if ($value === null) {
            return $emptyValue === null ? $this->managerDateEmptyDisplay : $emptyValue;
        }

        return date($format, $value);
    }

    /**
     * Transforms a date/time-related value according to the DateTime system format used for hidden values
     * @param string|int $value The value to transform (a Unix timestamp or mysql-format string)
     * @return string The formatted date
     */
    public function formatHidden($value): string
    {
        return $this->format($value, $this->managerDateHiddenFormat, false, '');
    }

    /**
     * Transforms a date/time-related value into the specified date-only DateTime format
     * @param string|int $value The value to transform (a Unix timestamp or mysql-format string)
     * @param bool $useOffset Whether to use the offset time (system setting) in the date calculation
     * @param string|null $emptyValue The text to show when the $value passed is empty
     * @return string The formatted date
     */
    public function formatDate($value, bool $useOffset = false, ?string $emptyValue = null): string
    {
        return $this->format($value, $this->managerDateFormat, $useOffset, $emptyValue);
    }

    /**
     * Transforms a date/time-related value into the specified time-only DateTime format
     * @param string|int $value The value to transform (a Unix timestamp or mysql-format string)
     * @param bool $useOffset Whether to use the offset time (system setting) in the date calculation
     * @param string|null $emptyValue The text to show when the $value passed is empty
     * @return string The formatted date
     */
    public function formatTime($value, bool $useOffset = false, ?string $emptyValue = null): string
    {
        return $this->format($value, $this->managerTimeFormat, $useOffset, $emptyValue);
    }

    /**
     * Transforms a date/time-related value into the specified DateTime format showing
     * both date and time, including an optional system-specified separator
     * @param string|int $value The value to transform (a Unix timestamp or mysql-format string)
     * @param bool $useOffset Whether to use the offset time (system setting) in the date calculation
     * @param string|null $emptyValue The text to show when the $value passed is empty
     * @return string The formatted date
     */
    public function formatDateTime($value, bool $useOffset = false, ?string $emptyValue = null): string
    {
        $managerDateTimeSeparator = $this->modx->getOption('manager_datetime_separator', null, ', ', true);
        $format = $this->managerDateFormat . $managerDateTimeSeparator . $this->managerTimeFormat;

        return $this->format($value, $format, $useOffset, $emptyValue);
    }

    /**
     * Formats a Resource-related date when applicable and includes localized default text to
     * represent conditions where a date is not present or applicable
     *
     * @param string|int $value The source date value to format
     * @param string $whichDate An identifier specifying the type of date being formatted
     * @param bool $useStandardEmptyValue Whether to use the default empty value defined in the system settings
     * @return string The formatted date or relevant text indicating an empty date value
     */
    public function formatResourceDate($value, string $whichDate = 'created', bool $useStandardEmptyValue = true): string
    {
        if ($useStandardEmptyValue) {
            return $this->formatDateTime($value, true, null);
        }
        switch ($whichDate) {
            case 'edited':
                $emptyValue = $this->modx->lexicon('unedited');
                break;
            case 'publish':
            case 'unpublish':
                $emptyValue = $this->modx->lexicon('notset');
                break;
            case 'published':
                $emptyValue = $this->modx->lexicon('unpublished');
                break;
            default:
                $emptyValue = $this->modx->lexicon('unknown');
        }
        $emptyValue = '(' . $emptyValue . ')';

        return $this->formatDateTime($value, true, $emptyValue);
    }

    /**
     * Formats a Package-related date when applicable and includes localized default text to
     * represent conditions where a date is not present or applicable
     *
     * @param string|int $value The source date value to format
     * @param string $whichDate An identifier specifying the type of date being formatted
     * @param bool $useStandardEmptyValue Whether to use the default empty value defined in the system settings
     * @return string The formatted date or relevant text indicating an empty date value
     */
    public function formatPackageDate($value, string $whichDate = 'created', bool $useStandardEmptyValue = true): string
    {
        if ($useStandardEmptyValue) {
            return $this->formatDateTime($value, true, null);
        }
        switch ($whichDate) {
            case 'installed':
                $emptyValue = $this->modx->lexicon('not_installed');
                break;
            case 'updated':
                $emptyValue = $this->modx->lexicon('not_updated');
                break;
            default:
                $emptyValue = $this->modx->lexicon('unknown');
        }
        $emptyValue = '(' . $emptyValue . ')';

        return $this->formatDateTime($value, true, $emptyValue);
    }
}

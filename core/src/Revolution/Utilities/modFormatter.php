<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Utilities;

use MODX\Revolution\modX;

/**
 * Provides utility methods for processors and controllers
 *
 * @package MODX\Revolution
 */
class modFormatter
{
    /**
     * A reference to the modX object.
     * @var modX $modx
     */
    public $modx = null;

    // DATE FORMATTER PROPERTIES

    /**
     * @var string $managerDateFormat The php datetime format for dates, as defined in the system settings
     */
    public $managerDateFormat = '';

    /**
     * @var string $managerTimeFormat The php datetime format for times, as defined in the system settings
     */
    public $managerTimeFormat = '';

    /**
     * @var string $managerDateHiddenFormat Standard mysql format required for date transformations
     */
    public $managerDateHiddenFormat = 'Y-m-d H:i:s';

    /**
     * @var array $managerDateEmptyValues A list of possible values representing an empty date
     */
    public $managerDateEmptyValues = [
        '',
        '-001-11-30 00:00:00',
        '-1-11-30 00:00:00',
        '1969-12-31 00:00:00',
        '0000-00-00 00:00:00',
        0,
        null
    ];

    /**
     * @var string $managerDateEmptyDisplay The text (if any) to show for empty dates
     */
    public $managerDateEmptyDisplay = '';


    public function __construct(modX $modx)
    {
        $this->modx =& $modx;
        $this->managerDateFormat = $this->modx->getOption('manager_date_format', null, 'Y-m-d', true);
        $this->managerTimeFormat = $this->modx->getOption('manager_time_format', null, 'H:i', true);
        $this->managerDateEmptyDisplay = $this->modx->getOption('manager_datetime_empty_value', null, '–', true);
    }

    /**
     * Provides final formatted output of a date, time, or combination of the two, based on system settings
     * @param string|int $value The value to transform (must be a unix timestamp or mysql-format string)
     * @param string $outputStyle Controls whether the output should be date only, time only, or a combination of the two
     * @param bool $useOffset Whether to use the offset time (system setting) in the date calculation
     * @param bool $useAlternateFormat Whether to override the system settings' format with a custom one
     * @param string $alternateFormat The custom format to use when overriding the system one
     * @return string The formatted date
     */
    public function formatManagerDateTime($value, string $outputStyle = 'combined', bool $useOffset = false, bool $useAlternateFormat = false, string $alternateFormat = ''): string
    {
        $offset = floatval($this->modx->getOption('server_offset_time', null, 0)) * 3600;
        $managerDateTimeSeparator = $this->modx->getOption('manager_datetime_separator', null, ', ', true);

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

        $value = $useOffset ? $value + $offset : $value ;

        if ($useAlternateFormat && !empty($alternateFormat)) {
            return date($alternateFormat, $value);
        }

        switch ($outputStyle) {
            case 'combined':
                $format = $this->managerDateFormat . $managerDateTimeSeparator . $this->managerTimeFormat;
                break;
            case 'date':
                $format = $this->managerDateFormat;
                break;
            case 'time':
                $format = $this->managerTimeFormat;
                break;
            // no default
        }

        return date($format, $value);
    }

    /**
     * Formats a Resource-related date when applicable and includes localized default text to
     * represent conditions where a date is not present
     *
     * @param string|int $value The source date value to format
     * @param string $whichDate One of five identifiers specifying the type of date being formatted
     * @param bool $useStandardEmptyValue Whether to use the default empty value defined in the system settings
     * @return string The formatted date or relevant text indicating an empty date value
     */
    public function getFormattedResourceDate($value, string $whichDate = 'created', bool $useStandardEmptyValue = true): string
    {
        if ($useStandardEmptyValue) {
            $emptyValue = $this->managerDateEmptyDisplay;
        } else {
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
        }
        return in_array($value, $this->managerDateEmptyValues)
            ? $emptyValue
            : $this->formatManagerDateTime($value, 'combined', true)
            ;
    }
}

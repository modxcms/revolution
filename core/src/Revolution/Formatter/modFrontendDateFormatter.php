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
use MODX\Revolution\Formatter\modManagerDateFormatter;
use MODX\Revolution\Formatter\modDateFormatConverter;

class modFrontendDateFormatter extends modManagerDateFormatter
{
    protected ?bool $autoConvertStrftime;
    public ?string $conversionRule;

    protected string $sourceFormatType = 'datetime';
    protected string $sourceFormat = '';
    protected ?string $destinationFormatType;
    protected ?string $destinationFormat;

    private modDateFormatConverter $converter;

    public function __construct(modX $modx)
    {
        parent::__construct($modx);
        $this->autoConvertStrftime = version_compare(phpversion(), '9.0.0', '>=');
        // Hard code testing vals ...
        // $this->autoConvertStrftime = true;
        // $this->hasIntlDateExt = false;
        // End HCV
        $lc = setlocale(LC_ALL, null);
        $msg = <<<LOG
            __construct():
                hasIntlDateExt: {$this->hasIntlDateExt}
                autoConvertStrftime: {$this->autoConvertStrftime}
                locale: {$lc}
                culture: {$this->modx->cultureKey}
                session lang: {$_SESSION['manager_language']}
        LOG;
        $this->modx->log(modX::LOG_LEVEL_ERROR, "\r{$msg}");
        // $this->modx->log(modX::LOG_LEVEL_ERROR, "\rSession: " . print_r($_SESSION, true));
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
        $msg = <<<LOG
            format():
                Val = {$value}
                Format: {$format}
                sourceFormat: {$this->sourceFormat}
                sourceFormatType: {$this->sourceFormatType}
        LOG;
        $this->modx->log(modX::LOG_LEVEL_ERROR, "\r{$msg}");
        if (!$this->autoConvertStrftime) {
            $this->setDateFn($this->sourceFormatType);
        } else {
            if ($this->sourceFormatType === 'strftime') {
                if ($this->hasIntlDateExt) {
                    $this->setDateFn('intl');
                    $this->setConversionRule();
                } else {
                    $this->setDateFn('datetime');
                    $this->setConversionRule('strftime->datetime');
                }
                $this->getConverter();
                // $this->modx->log(modX::LOG_LEVEL_ERROR, "\rGot converter!");
                $format = $this->converter->apply($format);
            }
        }
        return parent::format($value, $format);
    }

    /**
     * Sets this class's $conversionRule property value
     * @param string $rule A string in the form of 'fromPatternType->toPatternType'
     * that specifies the source to destination conversion
     */
    public function setConversionRule(string $rule = 'strftime->intl'): void
    {
        $this->conversionRule = $rule;
    }

    /**
     * Preserves the original formatting string for reference
     * @param string $sourceFormat The formatting pattern originally passed in
     * to this class's format method (before transformations, if any)
     */
    public function setSourceFormat(string $sourceFormat): void
    {
        if (strpos($sourceFormat, '%') !== false) {
            $this->setSourceFormatType('strftime');
        }
        $this->sourceFormat = trim($sourceFormat);
    }

    public function setSourceFormatType(string $formatType): void
    {
        // optional: set 'strftime' or 'datetime', could be 'intl' but that's typically going to be the preferred destination format
        $this->sourceFormatType = trim($formatType);
    }

    public function setDestinationFormatType(string $formatType): void
    {
        // optional: set 'intl' or 'datetime'
        $this->destinationFormatType = trim($formatType);
    }

    /**
     * Gets an instance of modDateFormatConverter
     */
    private function getConverter(): void
    {
        $this->converter = new modDateFormatConverter($this->modx, $this->conversionRule);
    }
}

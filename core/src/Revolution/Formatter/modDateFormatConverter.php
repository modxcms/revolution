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

class modDateFormatConverter
{
    /**
     * A reference to the modX object.
     * @var modX $modx
     */
    protected ?modX $modx;

    protected string $originalFormat;

    protected string $fromFormat;
    protected string $toFormat;

    private const FORMAT_CONVERTERS_MAP = [
        'strftime' => [
            'datetime' => 'strftimeToDatetime',
            'intl' => 'strftimeToIntl'
        ],
        'datetime' => [
            'intl' => 'datetimeToIntl'
        ]
    ];

    private ?string $mapName;
    private array $map = [];

    public function __construct(modX $modx, string $conversionRule = 'strftime->intl')
    {
        $this->modx =& $modx;
        $conversionRule = trim($conversionRule);
        if (empty($conversionRule)) {
            // log warn
            return;
        }
        if (strpos($conversionRule, '->') === false) {
            // log warn
            return;
        }
        $rule = explode('->', $conversionRule);
        $this->fromFormat = $rule[0];
        $this->toFormat = $rule[1];
    }

    public function apply(string $format): string
    {
        $format = trim($format);
        $this->originalFormat = $format;
        if (!$this->getConversionMap()) {
            // log err
            return $format;
        }
        $method = $this->getConversionMethod();
        if (!empty($method) && method_exists($this, $method)) {
            $format = $this->$method($format);
            $this->modx->log(modX::LOG_LEVEL_ERROR, "\rNew format = {$format}");
            return $format;
        }
        // log warn
        return $format;
    }

    private function getConversionMap(): bool
    {
        $this->mapName = array_key_exists($this->fromFormat, self::FORMAT_CONVERTERS_MAP) && array_key_exists($this->toFormat, self::FORMAT_CONVERTERS_MAP[$this->fromFormat])
            ? self::FORMAT_CONVERTERS_MAP[$this->fromFormat][$this->toFormat]
            : null
            ;
        if (!$this->mapName) {
            // log err
            return false;
        }
        $file = $this->mapName . '.map.php';
        $filePath = __DIR__ . '/' . ltrim($file, '/');
        if (!file_exists($filePath)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "\rMap file at {$filePath} not found, aborting!");
            return false;
        }
        // $this->modx->log(modX::LOG_LEVEL_ERROR, "\rGetting map file {$file} from {$filePath}...");
        $this->map = require $file;
        // $this->modx->log(modX::LOG_LEVEL_ERROR, "\rGot conversion map!");
        return true;
    }

    private function getConversionMethod(): string
    {
        if (!$this->fromFormat || !$this->toFormat) {
            // log err
            return '';
        }
        return strtolower($this->fromFormat) . 'To' . ucfirst(strtolower($this->toFormat));
    }

    private function strftimeToIntl(string $format): string
    {
        $this->prepareEscapedFormatting($format);
        if (preg_match_all('/%[\w]/', $format, $parts, PREG_PATTERN_ORDER)) {
            foreach ($parts[0] as $part) {
                $replacement = $this->map[$part];
                // Handle pre-defined patterns, defined by {predef:const1:const2}
                if (in_array($part, ['%X', '%x', '%c'])) {
                    $replacement = '{predef:' . implode(':', $replacement) . '}';
                    /*
                        Intl pre-defined format equivalents can not also contain other
                        patterns or characters. Here, if a strftime pre-defined pattern is
                        found, all other information in the original format is discarded
                        to ensure a valid mapping is created.
                    */
                    if (strlen($format) > 2) {
                        $msg = "[Make into lexicon] A pre-defined strftime format ({$part}) was found in the original format string to be converted ({$this->originalFormat}). Other characters and/or formats in the original format were discarded to ensure a valid mapping to Intl.";
                        $this->modx->log(modX::LOG_LEVEL_WARN, $msg);
                    }
                    $format = $replacement;
                    break;
                }
                $format = str_replace($part, $replacement, $format);
            }
        }
        return $format;
    }

    private function strftimeToDatetime(string $format): string
    {
        $this->prepareEscapedFormatting($format);
        if (preg_match_all('/%[\w]/', $format, $parts, PREG_PATTERN_ORDER)) {
            foreach ($parts[0] as $part) {
                $replacement = $this->map[$part];
                $format = str_replace($part, $replacement, $format);
            }
        }
        return $format;
    }

    /**
     * Provide basic transformation of string literals in formatting pattern
     */
    private function prepareEscapedFormatting(string &$format): void
    {
        if (strpos($format, '%%') !== false) {
            preg_match_all('/%%[\w]/', $format, $escapedParts, PREG_PATTERN_ORDER);
            foreach ($escapedParts[0] as $escapedPart) {
                $replacement = $this->toFormat === 'intl'
                    ? "'{$escapedPart[0]}{$escapedPart[2]}'"
                    : $escapedPart[0] . "\\" . $escapedPart[2]
                    ;
                $format = str_replace($escapedPart, $replacement, $format);
            }
            // If any '%%' sequences remain, they indicate a literal '%'
            if (strpos($format, '%%') !== false) {
                $format = str_replace('%%', '%', $format);
            }
        }
    }
}

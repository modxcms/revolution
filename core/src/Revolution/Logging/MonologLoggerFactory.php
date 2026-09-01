<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use MODX\Revolution\modX;
use Psr\Log\LoggerInterface;
use xPDO\Cache\xPDOCacheManager;

/**
 * Builds and installs the default Monolog logger when log_monolog is enabled.
 *
 * Disabled (default): no container binding and no setLogger() call — legacy
 * FILE/ECHO/HTML behaviour is unchanged.
 *
 * Enabled: creates Monolog → binds LoggerInterface in the DI container →
 * setLogger(), so PSR-3 replaces legacy FILE/ECHO/HTML output.
 */
final class MonologLoggerFactory
{
    /**
     * Opt in to Monolog: create, bind LoggerInterface, and setLogger().
     */
    public static function apply(modX $modx): void
    {
        if (!(bool) $modx->getOption('log_monolog', null, false)) {
            return;
        }

        $logger = self::create($modx);

        if ($modx->services->has(LoggerInterface::class)) {
            $modx->services->offsetUnset(LoggerInterface::class);
        }
        $modx->services->add(LoggerInterface::class, $logger);
        $modx->setLogger($logger);
    }

    /**
     * Create a Monolog logger writing to the resolved error.log path.
     */
    public static function create(modX $modx): LoggerInterface
    {
        [$filepath, $filename] = self::resolveLogPath($modx);

        if (!is_dir($filepath)) {
            $cacheManager = $modx->getCacheManager();
            if ($cacheManager) {
                $cacheManager->writeTree($filepath);
            } else {
                @mkdir($filepath, 0755, true);
            }
        }

        $logger = new Logger('modx');
        // Debug: modX already filters by logLevel before dispatch.
        $logger->pushHandler(new StreamHandler($filepath . $filename, Level::Debug));

        return $logger;
    }

    /**
     * Resolve the same path used by the legacy FILE log target / Error Log UI.
     *
     * @return array{0: string, 1: string} filepath (with trailing slash), filename
     */
    private static function resolveLogPath(modX $modx): array
    {
        $target = $modx->getLogTarget();
        $options = is_array($target) && isset($target['options']) && is_array($target['options'])
            ? $target['options']
            : [];

        $filename = !empty($options['filename'])
            ? (string) $options['filename']
            : (string) $modx->getOption('error_log_filename', null, 'error.log');
        if ($filename === '') {
            $filename = 'error.log';
        }

        if (!empty($options['filepath'])) {
            $filepath = rtrim((string) $options['filepath'], '/') . '/';
        } else {
            $customPath = (string) $modx->getOption('error_log_filepath', null, '');
            $filepath = $customPath !== ''
                ? rtrim($customPath, '/') . '/'
                : $modx->getCachePath() . xPDOCacheManager::LOG_DIR;
        }

        return [$filepath, $filename];
    }
}

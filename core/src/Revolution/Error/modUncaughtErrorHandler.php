<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Error;

use MODX\Revolution\modX;
use Throwable;

/**
 * Catches uncaught exceptions and fatal PHP errors during web requests and
 * responds with an HTTP 500 via modX::sendError('fatal').
 *
 * @package MODX\Revolution\Error
 */
class modUncaughtErrorHandler
{
    /** @var modX */
    private $modx;

    /** @var bool */
    private static $responded = false;

    /** @var self|null */
    private static $instance = null;

    /**
     * @param modX $modx
     */
    private function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    /**
     * Register exception and shutdown handlers for the current web request.
     *
     * @param modX $modx
     */
    public static function register(modX $modx)
    {
        if ((defined('XPDO_CLI_MODE') && XPDO_CLI_MODE) || !self::isEnabled($modx)) {
            return;
        }
        if (self::$instance !== null) {
            return;
        }

        self::$instance = new self($modx);
        set_exception_handler([self::$instance, 'handleException']);
        register_shutdown_function([self::$instance, 'handleShutdown']);
    }

    /**
     * @param modX $modx
     *
     * @return bool
     */
    public static function isEnabled(modX $modx)
    {
        return (bool)$modx->getOption('send_http_500_on_error', null, true);
    }

    /**
     * @param int $type
     *
     * @return bool
     */
    public static function isFatalErrorType($type)
    {
        return in_array($type, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true);
    }

    /**
     * @param Throwable $throwable
     */
    public function handleException(Throwable $throwable)
    {
        $this->respondWithFatalError(
            get_class($throwable) . ': ' . $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine()
        );
    }

    /**
     * Detect fatal errors that bypass set_error_handler.
     */
    public function handleShutdown()
    {
        if (self::$responded) {
            return;
        }

        $error = error_get_last();
        if (!is_array($error) || !self::isFatalErrorType($error['type'])) {
            return;
        }

        $this->respondWithFatalError(
            self::formatPhpError($error),
            isset($error['file']) ? $error['file'] : null,
            isset($error['line']) ? (int)$error['line'] : null
        );
    }

    /**
     * @param array $error
     *
     * @return string
     */
    public static function formatPhpError(array $error)
    {
        $message = isset($error['message']) ? $error['message'] : 'Unknown error';
        $file = isset($error['file']) ? $error['file'] : '';
        $line = isset($error['line']) ? $error['line'] : 0;

        return 'Fatal error: ' . $message . ' in ' . $file . ' on line ' . $line;
    }

    /**
     * @param string      $message
     * @param string|null $file
     * @param int|null    $line
     */
    public function respondWithFatalError($message, $file = null, $line = null)
    {
        if (self::$responded) {
            return;
        }
        self::$responded = true;

        if (headers_sent()) {
            $this->modx->log(
                modX::LOG_LEVEL_ERROR,
                $message . ' (headers already sent; unable to set HTTP 500)',
                '',
                '',
                $file !== null ? $file : '',
                $line !== null ? $line : 0
            );
            exit(1);
        }

        $this->modx->log(
            modX::LOG_LEVEL_FATAL,
            $message,
            '',
            '',
            $file !== null ? $file : '',
            $line !== null ? $line : 0
        );
    }

    /**
     * Reset internal state. Intended for unit tests only.
     */
    public static function resetForTesting()
    {
        self::$responded = false;
        self::$instance = null;
    }
}

<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

$tstart = microtime(true);

if (!function_exists('modx_site_unavailable')) {
    /**
     * @param string $errorMessage
     * @param string $errorPageTitle
     */
    function modx_site_unavailable($errorMessage, $errorPageTitle = '') {
        @include MODX_CORE_PATH . 'error/unavailable.include.php';
        /* if including is failed */
        echo "<html><title>Error 503: Site temporarily unavailable</title><body><h1>Error 503</h1><p>{$errorMessage}</p></body></html>";
        exit();
    }
}

/* define this as true in another entry file, then include this file to simply access the API
 * without executing the MODX request handler */
defined('MODX_API_MODE') or define('MODX_API_MODE', false);

/* include custom core config and define some important constants */
@include __DIR__ . '/config.core.php';

defined('MODX_CORE_PATH') or define('MODX_CORE_PATH', __DIR__ . '/core/');
defined('MODX_APP_CLASS') or define('MODX_APP_CLASS', 'modX');

/* include the composer autoloader */
if (!file_exists(MODX_CORE_PATH . 'vendor/autoload.php')) {
    modx_site_unavailable('Site temporarily unavailable - missing dependencies.');
}

require 'vendor/autoload.php';

/* start output buffering */
ob_start();

/* Create an instance of the application class */
try {
    $modx = modX::getInstance(MODX_APP_CLASS);
} catch (Exception $e) {
    modx_site_unavailable($e->getMessage());
}

if (!is_object($modx) || !($modx instanceof modX)) {
    ob_get_level() && @ob_end_flush();
    modx_site_unavailable('<a href="setup/">MODX not installed. Install now?</a>');
}

/* Set the actual start time */
$modx->startTime = $tstart;

/* Initialize the default 'web' context */
$modx->initialize('web');

/* execute the request handler */
if (!MODX_API_MODE) {
    $modx->handleRequest();
}
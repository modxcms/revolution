<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * This file exists only for BC purposes.
 */
if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
}

if (!file_exists(MODX_CORE_PATH . 'vendor/autoload.php')) {
    $errorMessage = 'Site temporarily unavailable; missing dependencies.';
    @include(MODX_CORE_PATH . 'error/unavailable.include.php');
    echo "<html><title>Error 503: Site temporarily unavailable</title><body><h1>Error 503</h1><p>{$errorMessage}</p></body></html>";
    exit();
}
require MODX_CORE_PATH . 'vendor/autoload.php';
require_once MODX_CORE_PATH . 'include/deprecated.php';


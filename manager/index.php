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
 * Initializes the modx manager
 *
 * @package modx
 * @subpackage manager
 */
@include(dirname(__FILE__) . '/config.core.php');
if (!defined('MODX_CORE_PATH')) define('MODX_CORE_PATH', dirname(__DIR__) . '/core/');
if (!defined('MODX_CONFIG_KEY')) define('MODX_CONFIG_KEY', 'config');

/* define this as true in another entry file, then include this file to simply access the API
 * without executing the MODX request handler */
if (!defined('MODX_API_MODE')) {
    define('MODX_API_MODE', false);
}

/* check for correct version of php */
$php_ver_comp = version_compare(phpversion(),'7.0');
if ($php_ver_comp < 0) {
    die('Wrong php version! You\'re using PHP version "'.phpversion().'", and MODX Revolution only works on 7.0 or higher.');
}

/* set the document_root */
if(!isset($_SERVER['DOCUMENT_ROOT']) || empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = str_replace($_SERVER['PATH_INFO'], '', str_replace('\\\\', '/', $_SERVER['PATH_TRANSLATED'])) . '/';
}

/* include the modX class */
if (!(require_once MODX_CORE_PATH . 'vendor/autoload.php')) {
    include MODX_CORE_PATH . 'error/unavailable.include.php';
    die('Site temporarily unavailable!');
}

/* @var \MODX\Revolution\modX $modx create the modX object */
$modx= new \MODX\Revolution\modX('', [\xPDO\xPDO::OPT_CONN_INIT => [\xPDO\xPDO::OPT_CONN_MUTABLE => true]]);
if (!is_object($modx) || !($modx instanceof \MODX\Revolution\modX)) {
    $errorMessage = '<a href="../setup/">MODX not installed. Install now?</a>';
    include MODX_CORE_PATH . 'error/unavailable.include.php';
    header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable');
    echo "<html><title>Error 503: Site temporarily unavailable</title><body><h1>Error 503</h1><p>{$errorMessage}</p></body></html>";
    exit();
}

$modx->initialize('mgr');

$modx->getRequest();
$modx->getParser();

if (!MODX_API_MODE) {
    $modx->request->handleRequest();
}
@session_write_close();
exit();

<?php
/**
 * Initializes the modx manager
 *
 * @package modx
 * @subpackage manager
 */
@include dirname(__FILE__) . '/config.core.php';
if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', dirname(__DIR__) . '/core/');
}

/* define this as true in another entry file, then include this file to simply access the API
 * without executing the MODX request handler */
if (!defined('MODX_API_MODE')) {
    define('MODX_API_MODE', false);
}

/* set the document_root */
if (!isset($_SERVER['DOCUMENT_ROOT']) || empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = str_replace($_SERVER['PATH_INFO'], '', str_replace('\\\\', '/', $_SERVER['PATH_TRANSLATED'])) . '/';
}

if (!file_exists(MODX_CORE_PATH . 'vendor/autoload.php')) {
    $errorMessage = 'Site temporarily unavailable; missing dependencies.';
    @include(MODX_CORE_PATH . 'error/unavailable.include.php');
    echo "<html><title>Error 503: Site temporarily unavailable</title><body><h1>Error 503</h1><p>{$errorMessage}</p></body></html>";
    exit();
}
require MODX_CORE_PATH . 'vendor/autoload.php';

$modx = new MODX\MODX('', [\xPDO\xPDO::OPT_CONN_INIT => [\xPDO\xPDO::OPT_CONN_MUTABLE => true]]);
if (!is_object($modx)) {
    $errorMessage = '<a href="../setup/">MODX not installed. Install now?</a>';
    include MODX_CORE_PATH . 'error/unavailable.include.php';
    header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable');
    echo "<html><title>Error 503: Site temporarily unavailable</title><body><h1>Error 503</h1><p>{$errorMessage}</p></body></html>";
    exit();
}

$modx->initialize('mgr');

$modx->getRequest();
$modx->getParser();
/** @var MODX\modManagerRequest $request */
$request = $modx->request;
if (!MODX_API_MODE) {
    $request->handleRequest();
}
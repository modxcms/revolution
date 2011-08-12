<?php
/**
 * Google minify implementation for MODX manager
 * @package modx
 * @subpackage minify
 */
@include dirname(dirname(__FILE__)) . '/config.core.php';
if (!defined('MODX_CORE_PATH')) define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');

/* set the document_root */
if(!isset($_SERVER['DOCUMENT_ROOT']) || empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = str_replace($_SERVER['PATH_INFO'], '', str_replace('\\\\', '/', $_SERVER['PATH_TRANSLATED'])) . '/';
}

/* include the modX class */
if (!(include_once MODX_CORE_PATH . 'model/modx/modx.class.php')) {
    include MODX_CORE_PATH . 'error/unavailable.include.php';
    die('Site temporarily unavailable!');
}

/* create the modX object */
$modx= new modX();
if (!is_object($modx) || !($modx instanceof modX)) {
    $errorMessage = '<a href="../setup/">MODX not installed. Install now?</a>';
    include MODX_CORE_PATH . 'error/unavailable.include.php';
    header('HTTP/1.1 503 Service Unavailable');
    echo "<html><title>Error 503: Site temporarily unavailable</title><body><h1>Error 503</h1><p>{$errorMessage}</p></body></html>";
    exit();
}
$modx->setDebug(E_ALL | E_STRICT);
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

$modx->initialize('mgr');
if (!$modx->user->hasSessionContext('mgr')) die();

$modx->getCacheManager();
$cachePath = $modx->getOption('core_path',null,MODX_CORE_PATH).'cache/mgr/min/';
if (!is_dir($cachePath) || !is_writable($cachePath)) {
    $modx->cacheManager->writeTree($cachePath);
}

/* minify stuff */
define('MINIFY_MIN_DIR', dirname(__FILE__));

/* setup minify config */
$min_allowDebugFlag = true;
$min_errorLogger = false;
$min_enableBuilder = false;
$min_cachePath = $cachePath;
$min_documentRoot = '';
$min_cacheFileLocking = (boolean)$modx->getOption('manager_js_cache_file_locking',null,true);
$min_serveOptions['bubbleCssImports'] = false;
$min_serveOptions['maxAge'] = (int)$modx->getOption('manager_js_cache_max_age',null,3600);
$min_serveOptions['minApp']['groupsOnly'] = false;
$min_serveOptions['minApp']['maxFiles'] = 50;
$min_symlinks = array();
$min_uploaderHoursBehind = 0;
$min_libPath = dirname(__FILE__) . '/lib';
@ini_set('zlib.output_compression', (int)$modx->getOption('manager_js_zlib_output_compression',null,0));

// setup include path
@set_include_path($min_libPath . PATH_SEPARATOR . get_include_path());
@set_time_limit(0);

require 'Minify.php';

Minify::$uploaderHoursBehind = $min_uploaderHoursBehind;
Minify::setCache(
    isset($min_cachePath) ? $min_cachePath : ''
    ,$min_cacheFileLocking
);

if ($min_documentRoot) {
    $_SERVER['DOCUMENT_ROOT'] = $min_documentRoot;
} elseif (0 === stripos(PHP_OS, 'win')) {
    Minify::setDocRoot(); // IIS may need help
}

$min_serveOptions['minifierOptions']['text/css']['symlinks'] = $min_symlinks;

if ($min_allowDebugFlag && isset($_GET['debug'])) {
    $min_serveOptions['debug'] = true;
}

if ($min_errorLogger) {
    require_once 'Minify/Logger.php';
    if (true === $min_errorLogger) {
        require_once 'FirePHP.php';
        Minify_Logger::setLogger(FirePHP::getInstance(true));
    } else {
        Minify_Logger::setLogger($min_errorLogger);
    }
}

// check for URI versioning
if (preg_match('/&\\d/', $_SERVER['QUERY_STRING'])) {
    $min_serveOptions['maxAge'] = 31536000;
}
if (isset($_GET['f']) || isset($_GET['g'])) {
    // serve!   
    Minify::serve('MinApp', $min_serveOptions);
} else {
    @session_write_close();
    exit();
}

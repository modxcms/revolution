<?php

/**
 * Check the MODX lexicons.
 *
 * Usage:
 *   php _build/lexicon/checklexicon.php [language] [excludedFolders]
 *
 * Reports (gitignored under _build/lexicon/):
 *   _missing.php, _superfluous.php, _variable.php
 *   _duplicates_identical.php, _duplicates_conflict.php
 *
 * @package modx
 * @subpackage build
 * @see https://github.com/modxcms/revolution/issues/14512
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
unset($mtime);

set_time_limit(0);

error_reporting(E_ALL);
ini_set('display_errors', true);

$buildConfig = dirname(dirname(__FILE__)) . '/build.config.php';

$included = false;
if (file_exists($buildConfig)) {
    $included = @include $buildConfig;
}
if (!$included) {
    die('"' . $buildConfig . '" was not found. Please make sure you have created one using the template of build.config.sample.php.');
}

unset($included);

if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', dirname(dirname(__DIR__)) . '/core/');
}

require MODX_CORE_PATH . 'vendor/autoload.php';
require dirname(__FILE__) . '/checklexicon.class.php';

use xPDO\xPDO;
use MODX\Revolution\Build\CheckLexicon;

if (!defined('MODX_BASE_PATH')) {
    define('MODX_BASE_PATH', dirname(MODX_CORE_PATH) . '/');
}
if (!defined('MODX_MANAGER_PATH')) {
    define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
}
if (!defined('MODX_CONNECTORS_PATH')) {
    define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');
}
if (!defined('MODX_ASSETS_PATH')) {
    define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');
}

if (!defined('XPDO_DSN')) {
    define('XPDO_DSN', 'mysql:host=localhost;dbname=modx;charset=utf8');
}
if (!defined('XPDO_DB_USER')) {
    define('XPDO_DB_USER', 'root');
}
if (!defined('XPDO_DB_PASS')) {
    define('XPDO_DB_PASS', '');
}
if (!defined('XPDO_TABLE_PREFIX')) {
    define('XPDO_TABLE_PREFIX', 'modx_');
}

$properties = array();
$f = dirname(dirname(__FILE__)) . '/build.properties.php';
$included = false;
if (file_exists($f)) {
    $included = @include $f;
}
if (!$included) {
    die('build.properties.php was not found. Please make sure you have created one using the template of build.properties.sample.php.');
}

unset($f, $included);

$xpdo = new xPDO(XPDO_DSN, XPDO_DB_USER, XPDO_DB_PASS,
    array(
        xPDO::OPT_TABLE_PREFIX => XPDO_TABLE_PREFIX,
        xPDO::OPT_CACHE_PATH => MODX_CORE_PATH . 'cache/',
    ),
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    )
);
$cacheManager = $xpdo->getCacheManager();
$xpdo->setLogLevel(xPDO::LOG_LEVEL_INFO);
$xpdo->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$xpdo->log(xPDO::LOG_LEVEL_INFO, 'Start lexicon check...');
flush();

$language = 'en';
if (!empty($argv) && $argc > 1) {
    $language = $argv[1];
}

$excluded = '';
if (!empty($argv) && $argc > 2) {
    $excluded = $argv[2];
}

$checkLexicon = new CheckLexicon($xpdo, array(
    'language' => $language,
    'excludedFolders' => $excluded
));
$result = $checkLexicon->process();
$xpdo->log(($result['success']) ? xPDO::LOG_LEVEL_INFO : xPDO::LOG_LEVEL_ERROR, $result['message']);

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";
flush();
exit();

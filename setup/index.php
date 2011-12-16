<?php
/*
 * MODX Revolution
 *
 * Copyright 2006-2012 by MODX, LLC. All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */
/**
 * Instantiates the setup program.
 *
 * @package modx
 * @subpackage setup
 */
/* do a little bit of environment cleanup if possible */
@ ini_set('magic_quotes_runtime', 0);
@ ini_set('magic_quotes_sybase', 0);

/* start session */
session_start();
$isCommandLine = php_sapi_name() == 'cli';
if ($isCommandLine) {
    foreach ($argv as $idx => $argv) {
        $p = explode('=',ltrim($argv,'--'));
        if (isset($p[1])) {
            $_REQUEST[$p[0]] = $p[1];
        }
    }
    if (!empty($_REQUEST['core_path']) && is_dir($_REQUEST['core_path'])) {
        define('MODX_CORE_PATH',$_REQUEST['core_path']);
    }
    if (!empty($_REQUEST['config_key'])) {
        define('MODX_CONFIG_KEY',$_REQUEST['config_key']);
    }
}

/* check for compatible PHP version */
define('MODX_SETUP_PHP_VERSION', phpversion());
$php_ver_comp = version_compare(MODX_SETUP_PHP_VERSION, '5.1.1');
if ($php_ver_comp < 0) {
    die('<html><head><title></title></head><body><h1>FATAL ERROR: MODX Setup cannot continue.</h1><p>Wrong PHP version! You\'re using PHP version '.MODX_SETUP_PHP_VERSION.', and MODX requires version 5.1.1 or higher.</p></body></html>');
}

/* make sure json extension is available */
if (!function_exists('json_encode')) {
    die('<html><head><title></title></head><body><h1>FATAL ERROR: MODX Setup cannot continue.</h1><p>MODX requires the PHP JSON extension! You\'re PHP configuration at version '.MODX_SETUP_PHP_VERSION.' does not appear to have this extension enabled. This should be a standard extension on PHP 5.2+; it is available as a PECL extension in 5.1.</p></body></html>');
}

/* make sure date.timezone is set for PHP 5.3.0+ users */
if (version_compare(MODX_SETUP_PHP_VERSION,'5.3.0') >= 0) {
    $phptz = @ini_get('date.timezone');
    if (empty($phptz)) {
        die('<html><head><title></title></head><body><h1>FATAL ERROR: MODX Setup cannot continue.</h1><p>To use PHP 5.3.0+, you must set the date.timezone setting in your php.ini. Please do set it to a proper timezone before proceeding. A list can be found <a href="http://us.php.net/manual/en/timezones.php">here</a>.</p></body></html>');
    }
}
if (!$isCommandLine) {
    $https = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : false;
    $installBaseUrl= (!$https || strtolower($https) != 'on') ? 'http://' : 'https://';
    $installBaseUrl .= $_SERVER['HTTP_HOST'];
    if ($_SERVER['SERVER_PORT'] != 80) $installBaseUrl= str_replace(':' . $_SERVER['SERVER_PORT'], '', $installBaseUrl);
    $installBaseUrl .= ($_SERVER['SERVER_PORT'] == 80 || ($https !== false || strtolower($https) == 'on')) ? '' : ':' . $_SERVER['SERVER_PORT'];
    $installBaseUrl .= $_SERVER['PHP_SELF'];
    define('MODX_SETUP_URL', $installBaseUrl);
} else {
    define('MODX_SETUP_URL','/');
}

/* session loop-back tester */
if (!$isCommandLine && (!isset($_GET['s']) || $_GET['s'] != 'set') && !isset($_SESSION['session_test'])) {
    $_SESSION['session_test']= 1;
    echo "<html><head><title>Loading...</title><script>window.location.href='" . MODX_SETUP_URL . "?s=set';</script></head><body></body></html>";
    exit ();
} elseif (!$isCommandLine && isset($_GET['s']) && $_GET['s'] == 'set' && !isset($_SESSION['session_test'])) {
    die('<html><head><title></title></head><body><h1>FATAL ERROR: MODX Setup cannot continue.</h1><p>Make sure your PHP session configuration is valid and working.</p></body></html>');
}

$setupPath= strtr(realpath(dirname(__FILE__)), '\\', '/') . '/';
define('MODX_SETUP_PATH', $setupPath);
$installPath= strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/';
define('MODX_INSTALL_PATH', $installPath);

if (!include(MODX_SETUP_PATH . 'includes/config.core.php')) {
    die('<html><head><title></title></head><body><h1>FATAL ERROR: MODX Setup cannot continue.</h1><p>Make sure you have uploaded all of the setup/ files; your setup/includes/config.core.php file is missing.</p></body></html>');
}
if (!include(MODX_SETUP_PATH . 'includes/modinstall.class.php')) {
    die('<html><head><title></title></head><body><h1>FATAL ERROR: MODX Setup cannot continue.</h1><p>Make sure you have uploaded all of the setup/ files; your setup/includes/modinstall.class.php file is missing.</p></body></html>');
}
$modInstall = new modInstall();
if ($modInstall->getService('lexicon','modInstallLexicon')) {
    $modInstall->lexicon->load('default');
}
$modInstall->findCore();
$modInstall->doPreloadChecks();
$requestClass = $isCommandLine ? 'request.modInstallCLIRequest' : 'request.modInstallRequest';
$modInstall->getService('request',$requestClass);
echo $modInstall->request->handle();
exit();
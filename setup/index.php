<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008, 2009 by the MODx Team.
 * All rights reserved.
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

/* set error reporting */
error_reporting(E_ALL & ~E_NOTICE);

/* start session */
session_start();

/* check for compatible PHP version */
define('MODX_SETUP_PHP_VERSION', phpversion());
$php_ver_comp = version_compare(MODX_SETUP_PHP_VERSION, '5.1.0');
if ($php_ver_comp < 0) {
    die('<html><head><title></title></head><body><h1>FATAL ERROR: MODx Setup cannot continue.</h1><p>Wrong PHP version! You\'re using PHP version '.MODX_SETUP_PHP_VERSION.', and MODx requires version 5.1.0 or higher.</p></body></html>');
}

/* session loop-back tester */
if ((!isset($_GET['s']) || $_GET['s'] != 'set') && !isset($_SESSION['session_test'])) {
    $_SESSION['session_test']= 1;
    $installBaseUrl= (!isset ($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) != 'on') ? 'http://' : 'https://';
    $installBaseUrl .= $_SERVER['HTTP_HOST'];
    if ($_SERVER['SERVER_PORT'] != 80)
        $installBaseUrl= str_replace(':' . $_SERVER['SERVER_PORT'], '', $installBaseUrl); /* remove port from HTTP_HOST */
    $installBaseUrl .= ($_SERVER['SERVER_PORT'] == 80 || isset ($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? '' : ':' . $_SERVER['SERVER_PORT'];
    echo "<html><head><title>Loading...</title><script>window.location.href='" . $installBaseUrl . $_SERVER['PHP_SELF'] . "?s=set';</script></head><body></body></html>";
    exit ();
} elseif (isset($_GET['s']) && $_GET['s'] == 'set' && !isset($_SESSION['session_test'])) {
    die('<html><head><title></title></head><body><h1>FATAL ERROR: MODx Setup cannot continue.</h1><p>Make sure your PHP session configuration is valid and working.</p></body></html>');
}

$setupPath= strtr(realpath(dirname(__FILE__)), '\\', '/') . '/';
define('MODX_SETUP_PATH', $setupPath);
$installPath= strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/';
define('MODX_INSTALL_PATH', $installPath);

if (!include(MODX_SETUP_PATH . 'includes/config.core.php')) {
    die('<html><head><title></title></head><body><h1>FATAL ERROR: MODx Setup cannot continue.</h1><p>Make sure you have uploaded all of the setup/ files; your setup/includes/config.core.php file is missing.</p></body></html>');
}
if (!include(MODX_SETUP_PATH . 'includes/modinstall.class.php')) {
    die('<html><head><title></title></head><body><h1>FATAL ERROR: MODx Setup cannot continue.</h1><p>Make sure you have uploaded all of the setup/ files; your setup/includes/modinstall.class.php file is missing.</p></body></html>');
}
$modInstall = new modInstall();
$modInstall->loadLang();
$modInstall->doPreloadChecks();
$modInstall->loadRequestHandler();
$modInstall->request->loadParser();
$modInstall->request->handle();
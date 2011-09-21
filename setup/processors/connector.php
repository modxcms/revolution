<?php
/**
 * Handles AJAX requests
 *
 * @package setup
 */
/* do a little bit of environment cleanup if possible */
@ini_set('magic_quotes_runtime',0);
@ini_set('magic_quotes_sybase',0);

/* start session */
session_start();

/* set error reporting */
error_reporting(E_ALL & ~E_NOTICE);

$setupPath= strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/';
define('MODX_SETUP_PATH', $setupPath);
$installPath= strtr(realpath(dirname(dirname(dirname(__FILE__)))), '\\', '/') . '/';
define('MODX_INSTALL_PATH', $installPath);

if (!@include(MODX_SETUP_PATH . 'includes/config.core.php')) die('Error loading core files!');
require_once MODX_CORE_PATH . 'xpdo/xpdo.class.php';
require_once MODX_SETUP_PATH . 'includes/modinstall.class.php';

$install = new modInstall();
$install->getService('lexicon','modInstallLexicon');
$install->lexicon->load('default');
$install->getService('request','request.modInstallConnectorRequest');
$install->request->handle();
@session_write_close();
exit();
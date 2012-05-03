<?php
/*
 * MODX Revolution
 *
 * Copyright 2006-2012 by MODX, LLC.
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
 *
 */
/**
 * Initializes the modx manager
 *
 * @package modx
 * @subpackage manager
 */
@include dirname(__FILE__) . '/config.core.php';
if (!defined('MODX_CORE_PATH')) define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');

/* define this as true in another entry file, then include this file to simply access the API
 * without executing the MODX request handler */
if (!defined('MODX_API_MODE')) {
    define('MODX_API_MODE', false);
}

/* check for correct version of php */
$php_ver_comp = version_compare(phpversion(),'5.1.0');
if ($php_ver_comp < 0) {
    die('Wrong php version! You\'re using PHP version "'.phpversion().'", and MODX Revolution only works on 5.1.0 or higher.');
}

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
$modx= new modX('', array(xPDO::OPT_CONN_INIT => array(xPDO::OPT_CONN_MUTABLE => true)));
if (!is_object($modx) || !($modx instanceof modX)) {
    $errorMessage = '<a href="../setup/">MODX not installed. Install now?</a>';
    include MODX_CORE_PATH . 'error/unavailable.include.php';
    header('HTTP/1.1 503 Service Unavailable');
    echo "<html><title>Error 503: Site temporarily unavailable</title><body><h1>Error 503</h1><p>{$errorMessage}</p></body></html>";
    exit();
}

$modx->initialize('mgr');

$modx->getRequest();
$modx->getParser();

if (isset($modx) && is_object($modx) && $modx instanceof modX) {
    if (!$modx->getRequest()) {
        $modx->log(modX::LOG_LEVEL_FATAL,"Could not load the MODX manager request object.");
    }
    if (!MODX_API_MODE) {
        $modx->request->handleRequest();
    }
}
@session_write_close();
exit();

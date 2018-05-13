<?php
/**
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package modx
 * @subpackage connectors
 */

$included = defined('MODX_CONNECTOR_INCLUDED') || defined('MODX_CORE_PATH');

/* retrieve or define MODX_CORE_PATH */
if (!defined('MODX_CORE_PATH')) {
    if (file_exists(dirname(__FILE__) . '/config.core.php')) {
        include dirname(__FILE__) . '/config.core.php';
    } else {
        define('MODX_CORE_PATH', dirname(__DIR__) . '/core/');
    }

    /* anonymous access for security/login action */
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'security/login') {
        define('MODX_REQP', false);
    }
}

if (!file_exists(MODX_CORE_PATH . 'vendor/autoload.php')) {
    $errorMessage = 'Site temporarily unavailable; missing dependencies.';
    @include(MODX_CORE_PATH . 'error/unavailable.include.php');
    echo "<html><title>Error 503: Site temporarily unavailable</title><body><h1>Error 503</h1><p>{$errorMessage}</p></body></html>";
    exit();
}
require MODX_CORE_PATH . 'vendor/autoload.php';

/* load modX instance */
$modx = new MODX\MODX('', [xPDO::OPT_CONN_INIT => [xPDO::OPT_CONN_MUTABLE => true]]);

/* initialize the proper context */
$ctx = isset($_REQUEST['ctx']) && !empty($_REQUEST['ctx']) && is_string($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'mgr';
$modx->initialize($ctx);

/* check for anonymous access or for a context access policy - return error on failure */
if (defined('MODX_REQP') && MODX_REQP === false) {
} elseif (!is_object($modx->context) || !$modx->context->checkPolicy('load')) {
    header("Content-Type: application/json; charset=UTF-8");
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Not Authorized');
    echo json_encode([
        'success' => false,
        'code' => 401,
    ]);
    @session_write_close();
    die();
}

/* set manager language in manager context */
if ($ctx == 'mgr') {
    $ml = $modx->getOption('cultureKey', null, 'en');
    if ($ml != 'en') {
        $modx->lexicon->load($ml . ':core:default');
        $modx->setOption('cultureKey', $ml);
    }
}

/* handle the request */
$connectorRequestClass = $modx->getOption('modConnectorRequest.class', null, 'modConnectorRequest');
$modx->config['modRequest.class'] = $connectorRequestClass;
$modx->getRequest();
$modx->request->sanitizeRequest();

if (!$included) {
    $modx->request->handleRequest();
}

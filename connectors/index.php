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
        define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');
    }

    /* anonymous access for security/login action */
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'security/login') {
        define('MODX_REQP', false);
    }
}

/* include modX class - return error on failure */
if (!include_once(MODX_CORE_PATH . 'model/modx/modx.class.php')) {
    header("Content-Type: application/json; charset=UTF-8");
    header('HTTP/1.1 404 Not Found');
    echo json_encode(array(
        'success' => false,
        'code' => 404,
    ));
    die();
}

/* load modX instance */
$modx = new modX('', array(xPDO::OPT_CONN_INIT => array(xPDO::OPT_CONN_MUTABLE => true)));

/* initialize the proper context */
$ctx = isset($_REQUEST['ctx']) && !empty($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'mgr';
$modx->initialize($ctx);

/* check for anonymous access or for a context access policy - return error on failure */
if (defined('MODX_REQP') && MODX_REQP === false) {
} else if (!is_object($modx->context) || !$modx->context->checkPolicy('load')) {
    header("Content-Type: application/json; charset=UTF-8");
    header('HTTP/1.1 401 Not Authorized');
    echo json_encode(array(
        'success' => false,
        'code' => 401,
    ));
    @session_write_close();
    die();
}

/* set manager language in manager context */
if ($ctx == 'mgr') {
    $ml = $modx->getOption('manager_language',null,'en');
    if ($ml != 'en') {
        $modx->lexicon->load($ml.':core:default');
        $modx->setOption('cultureKey',$ml);
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

<?php
/**
 * @package modx
 * @var modX $modx
 */
define('MODX_CONNECTOR_INCLUDED', 1);
define('MODX_REQP', false);
require_once dirname(__FILE__) . '/index.php';
$_SERVER['HTTP_MODAUTH'] = $modx->user->getUserToken($modx->context->get('key'));
/** @var \MODX\modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest(['location' => 'System', 'action' => 'ConfigJs']);

<?php
define('MODX_REQP',false);
require_once dirname(dirname(__FILE__)).'/index.php';
$_SERVER['HTTP_MODAUTH'] = $_SESSION["modx.{$modx->context->get('key')}.user.token"];
$modx->request->handleRequest(array('location' => 'system','action' => 'config.js'));
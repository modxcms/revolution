<?php
define('MODX_CONNECTOR_INCLUDED', 1);
define('MODX_REQP',false);
require_once dirname(__DIR__).'/index.php';
$modx->request->handleRequest(array('location' => 'security','action' => 'login'));

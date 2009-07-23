<?php
define('MODX_REQP',false);
require_once dirname(dirname(__FILE__)).'/index.php';
$modx->request->handleRequest(array('location' => 'system','action' => 'config.js'));
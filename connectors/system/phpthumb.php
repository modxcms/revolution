<?php
require_once dirname(dirname(__FILE__)).'/index.php';
$_SERVER['HTTP_MODAUTH'] = $modx->site_id;
$modx->request->handleRequest(array('location' => 'system','action' => 'phpthumb'));

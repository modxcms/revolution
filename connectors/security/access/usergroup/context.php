<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/index.php';
$modx->request->handleRequest(array('location' => 'security/access/usergroup/context'));
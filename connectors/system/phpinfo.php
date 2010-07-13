<?php
/**
 * Loads phpinfo()
 *
 * @package modx
 * @subpackage manager.system
 */
require_once dirname(dirname(__FILE__)).'/index.php';
$modx->request->handleRequest(array('location' => 'system','action' => 'phpinfo'));
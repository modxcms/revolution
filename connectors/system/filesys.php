<?php
require_once dirname(dirname(__FILE__)).'/index.php';
switch ($_REQUEST['action']) {
	case 'removeFile':
        $modx->request->handleRequest(array('location' => 'system/filesys/file','action' => 'remove'));
		break;
	case 'updateFile':
		$modx->request->handleRequest(array('location' => 'system/filesys/file','action' => 'update'));
		break;
	case 'unzipFile':
		$modx->request->handleRequest(array('location' => 'system/filesys/file','action' => 'unzip'));
		break;
	case 'createFolder':
		$modx->request->handleRequest(array('location' => 'system/filesys/folder','action' => 'create'));
		break;
	case 'removeFolder':
		$modx->request->handleRequest(array('location' => 'system/filesys/folder','action' => 'remove'));
		break;
}
<?php
/**
 * @package modx
 * @subpackage processors.system.filesys.file
 */

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!file_exists($_POST['path']))
	return $modx->error->failure($modx->lexicon('file_err_nf'));


// open file
if (!$handle = fopen($_POST['path'],'w'))
	 return $modx->error->failure($modx->lexicon('file_err_open').$_POST['path']);


// write to opened file
if (fwrite($handle,$_POST['content']) === false)
	return $modx->error->failure($modx->lexicon('file_err_save'));

fclose($handle);

return $modx->error->success();
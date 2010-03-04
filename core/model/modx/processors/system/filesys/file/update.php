<?php
/**
 * @package modx
 * @subpackage processors.system.filesys.file
 */

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!file_exists($scriptProperties['path']))
	return $modx->error->failure($modx->lexicon('file_err_nf'));


// open file
if (!$handle = fopen($scriptProperties['path'],'w'))
	 return $modx->error->failure($modx->lexicon('file_err_open').$scriptProperties['path']);


// write to opened file
if (fwrite($handle,$scriptProperties['content']) === false)
	return $modx->error->failure($modx->lexicon('file_err_save'));

fclose($handle);

return $modx->error->success();
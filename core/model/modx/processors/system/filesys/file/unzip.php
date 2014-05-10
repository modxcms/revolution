<?php
/**
 * @package modx
 * @subpackage processors.system.filesys.file
 */

if (!$modx->hasPermission('file_manager')) { return $modx->error->failure($modx->lexicon('permission_denied')); }

$modx->getService('fileHandler', 'modFileHandler');

$fileobj = $modx->fileHandler->make($scriptProperties['path'].$scriptProperties['file']);

if (!$fileobj->getParentDirectory()->isWritable()) {
	return $modx->error->failure($modx->lexicon('file_err_unzip_invalid_path'));
}

if (!$fileobj->exists()) {
	return $modx->error->failure($modx->lexicon('file_err_nf'));
}

if (!$fileobj->unpack()) {
	return $modx->error->failure($modx->lexicon('file_err_unzip'));
}


return $modx->error->success();
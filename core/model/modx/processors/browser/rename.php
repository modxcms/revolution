<?php
/**
 * Renames a file
 *
 * @param string $file The file to rename
 * @param string $newname The new name for the file
 *
 * @package modx
 * @subpackage processors.browser
 */
if (!$modx->hasPermission('directory_update')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['path'])) return $modx->error->failure($modx->lexicon('file_err_ns'));
if (empty($scriptProperties['newname'])) return $modx->error->failure($modx->lexicon('name_err_ns'));

$modx->getService('fileHandler','modFileHandler');
$root = $modx->fileHandler->getBasePath();

/* generate modFileSystemResource from path */
$fsResource = $modx->fileHandler->make($root.$scriptProperties['path']);
$directory = $fsResource->getParentDirectory();

/* make sure parent dir is a directory and writable */
if (!($directory instanceof modDirectory)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!$directory->isReadable() || !$directory->isWritable()) {
	return $modx->error->failure($modx->lexicon('file_folder_err_perms'));
}

$newPath = $directory->getPath().$scriptProperties['newname'];

/* rename the original file/directory */
if (!$fsResource->rename($newPath)) {
    return $modx->error->failure($modx->lexicon('file_err_rename'));
}

$modx->logManagerAction('directory_rename','',$fsResource->getPath());

return $modx->error->success();
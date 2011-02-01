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

/* get working context */
$wctx = isset($scriptProperties['wctx']) && !empty($scriptProperties['wctx']) ? $scriptProperties['wctx'] : '';
if (!empty($wctx)) {
    $workingContext = $modx->getContext($wctx);
    if (!$workingContext) {
        return $modx->error->failure($modx->error->failure($modx->lexicon('permission_denied')));
    }
} else {
    $workingContext =& $modx->context;
}

$modx->getService('fileHandler','modFileHandler', '', array('context' => $workingContext->get('key')));
$root = $modx->fileHandler->getBasePath();
if ($workingContext->getOption('filemanager_path_relative',true)) {
    $root = $workingContext->getOption('base_path','').$root;
}

/* generate modFileSystemResource from path */
$fullPath = $root.$scriptProperties['path'];
$fsResource = $modx->fileHandler->make($fullPath);
$directory = $fsResource->getParentDirectory();

/* make sure parent dir is a directory and writable */
if (!($directory instanceof modDirectory)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid').$fullPath);
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

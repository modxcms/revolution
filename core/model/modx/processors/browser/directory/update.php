<?php
/**
 * Renames a directory.
 *
 * @param string $dir The directory to rename
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
if (!$modx->hasPermission('directory_update')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['dir'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));
if (empty($scriptProperties['name'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));

/* get base paths and sanitize incoming paths */
$modx->getService('fileHandler','modFileHandler');
$root = $modx->fileHandler->getBasePath();

/* instantiate modDirectory object */
$oldDirectory = $modx->fileHandler->make($root.$scriptProperties['dir']);

/* make sure is a directory and writable */
if (!($oldDirectory instanceof modDirectory)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!$oldDirectory->isReadable() || !$oldDirectory->isWritable()) {
	return $modx->error->failure($modx->lexicon('file_folder_err_perms'));
}

/* sanitize new path */
$newPath = $modx->fileHandler->sanitizePath($scriptProperties['name']);
$newPath = $modx->fileHandler->postfixSlash($newPath);

/* rename the dir */
if (!$oldDirectory->rename($newPath)) {
    return $modx->error->failure($modx->lexicon('file_folder_err_rename'));
}

$modx->logManagerAction('directory_rename','',$oldDirectory->getPath());

return $modx->error->success();
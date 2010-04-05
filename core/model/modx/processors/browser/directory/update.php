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
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['dir'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));
if (empty($scriptProperties['name'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));

/* get base paths and sanitize incoming paths */
$modx->getService('fileHandler','modFileHandler');
$root = $modx->fileHandler->getBasePath();
$oldPath = $modx->fileHandler->sanitizePath($scriptProperties['dir']);
$oldPath = $modx->fileHandler->postfixSlash($oldPath);
$oldPath = $root.$oldPath;

/* make sure is a directory and writable */
if (!is_dir($olddir)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($olddir) || !is_writable($olddir)) {
	return $modx->error->failure($modx->lexicon('file_folder_err_perms'));
}

/* sanitize new path */
$newPath = $modx->fileHandler->sanitizePath($scriptProperties['name']);
$newPath = $modx->fileHandler->postfixSlash($newPath);

/* rename the dir */
if (!@rename($oldPath,$newPath)) {
    return $modx->error->failure($modx->lexicon('file_folder_err_rename'));
}

return $modx->error->success();
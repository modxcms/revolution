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
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['path'])) return $modx->error->failure($modx->lexicon('file_err_ns'));
if (empty($scriptProperties['newname'])) return $modx->error->failure($modx->lexicon('name_err_ns'));

$modx->getService('fileHandler','modFileHandler');
$root = $modx->fileHandler->getBasePath();

$oldFile = $root.$scriptProperties['path'];
$oldPath = $modx->fileHandler->getDirectoryFromFile($oldFile);
$oldPath = $modx->fileHandler->sanitizePath($oldPath);
$oldPath = $modx->fileHandler->postfixSlash($oldPath);

if (!is_readable($oldPath) || !is_writable($oldPath)) {
    return $modx->error->failure($modx->lexicon('file_err_perms_rename'));
}

$newPath = $modx->fileHandler->sanitizePath($scriptProperties['newname']);
$newPath = $oldPath.$newPath;

if (!@rename($oldFile,$newPath)) {
    return $modx->error->failure($modx->lexicon('file_err_rename'));
}

return $modx->error->success();
<?php
/**
 * Gets the contents of a file
 *
 * @param string $file The absolute path of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

/* format filename */
$filename = rawurldecode($scriptProperties['file']);

$modx->getService('fileHandler','modFileHandler');
$file = $modx->fileHandler->make($filename);

if (!$file->exists()) return $modx->error->failure($modx->lexicon('file_err_nf'));
if (!$file->isReadable()) {
    return $modx->error->failure($modx->lexicon('file_err_perms'));
}

$fa = array(
    'name' => $file->getPath(),
    'size' => $file->getSize(),
    'last_accessed' => $file->getLastAccessed(),
    'last_modified' => $file->getLastModified(),
    'content' => $file->getContents(),
);

return $modx->error->success('',$fa);
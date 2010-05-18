<?php
/**
 * Updates a file.
 *
 * @param string $file The absolute path of the file
 * @param string $name Will rename the file if different
 * @param string $content The new content of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_update')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

/* get base paths and sanitize incoming paths */
$filename = rawurldecode($scriptProperties['file']);

/* create modFile object */
$modx->getService('fileHandler','modFileHandler');
$file = $modx->fileHandler->make($filename);

/* verify file exists */
if (!$file->exists()) return $modx->error->failure($modx->lexicon('file_err_nf'));

/* write file */
$file->setContent($scriptProperties['content']);
$file->save();

/* rename if necessary */
$newPath= $scriptProperties['name'];

if ($file->getPath() != $newPath) {
    if (!$file->rename($newPath)) {
        return $modx->error->failure($modx->lexicon('file_err_rename'));
    }
}

$modx->logManagerAction('file_update','',$file->getPath());

return $modx->error->success('',array(
    'file' => rawurlencode($file->getPath()),
));
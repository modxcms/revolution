<?php
/**
 * Upload files to a directory
 *
 * @param string $path The target directory
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_upload')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['path'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));


/** @var modMediaSource $source */
$modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($modx);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$success = $source->uploadToFolder($scriptProperties['path'],$_FILES);

if (empty($success)) {
    $msg = '';
    $errors = $source->getErrors();
    foreach ($errors as $k => $msg) {
        $modx->error->addField($k,$msg);
    }
    return $modx->error->failure($msg);
}
return $modx->error->success();
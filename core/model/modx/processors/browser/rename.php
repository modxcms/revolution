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
$oldDirectory = $scriptProperties['path'];

/** @var modMediaSource $source */
$source = $modx->getObject('modMediaSource',1);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$success = $source->renameFolder($oldDirectory,$scriptProperties['newname']);

if (empty($success)) {
    $msg = '';
    $errors = $source->getErrors();
    foreach ($errors as $k => $msg) {
        $modx->error->addField($k,$msg);
    }
    return $modx->error->failure($msg);
}
return $modx->error->success();

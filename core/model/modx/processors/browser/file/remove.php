<?php
/**
 * Removes a file.
 *
 * @param string $file The name of the file.
 * @param boolean $prependPath If true, will prepend the rb_base_dir to the file
 * name.
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_remove')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['file'])) return $modx->error->failure($modx->lexicon('file_err_ns'));


/** @var modMediaSource $source */
$modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($modx);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$success = $source->removeFile($scriptProperties['file']);

if (empty($success)) {
    $msg = '';
    $errors = $source->getErrors();
    foreach ($errors as $k => $msg) {
        $modx->error->addField($k,$msg);
    }
    return $modx->error->failure();
}
return $modx->error->success();
<?php
/**
 * Moves a file/directory.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
if (!$modx->hasPermission('directory_update')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['from'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));
if (empty($scriptProperties['to'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));
$source = $modx->getOption('source',$scriptProperties,1);

/** @var modMediaSource $source */
$modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($modx,$source);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$success = $source->moveObject($scriptProperties['from'],$scriptProperties['to']);
if (!$success) {
    $errors = $source->getErrors();
    foreach ($errors as $k => $msg) {
        $modx->error->addField($k,$msg);
    }
    return $modx->error->failure($modx->error->message);
}
return $modx->error->success();

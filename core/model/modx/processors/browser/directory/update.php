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
$source = $modx->getOption('source',$scriptProperties,1);

/** @var modMediaSource $source */
$modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($modx,$source);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$success = $source->renameContainer($scriptProperties['dir'],$scriptProperties['name']);

if (!$success) {
    $msg = '';
    $errors = $source->getErrors();
    foreach ($errors as $k => $msg) {
        $modx->error->addField($k,$msg);
    }
    return $modx->error->failure($msg);
}
return $modx->error->success();
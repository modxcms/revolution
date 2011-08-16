<?php
/**
 * Chmod a directory
 *
 * @param string $mode The mode to chmod to
 * @param string $dir The absolute path of the dir
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 * @package modx
 * @subpackage processors.browser.directory
 */
if (!$modx->hasPermission('directory_chmod')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['mode'])) return $modx->error->failure($modx->lexicon('file_err_chmod_ns'));
if (empty($scriptProperties['dir'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));

/** @var modMediaSource|modFileMediaSource $source */
$modx->loadClass('modMediaSource');
$source = modMediaSource::getDefaultSource($modx);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$success = $source->chmodFolder($scriptProperties['dir'],$scriptProperties['mode']);

if (!$success) {
    $msg = '';
    $errors = $source->getErrors();
    foreach ($errors as $k => $msg) {
        $modx->error->addField($k,$msg);
    }
    return $modx->error->failure($msg);
}
return $modx->error->success();

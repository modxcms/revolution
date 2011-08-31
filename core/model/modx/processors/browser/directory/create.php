<?php
/**
 * Create a directory.
 *
 * @param string $name The name of the directory to create
 * @param string $parent The parent directory
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
if (!$modx->hasPermission('directory_create')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['name'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));
if (empty($scriptProperties['parent'])) $scriptProperties['parent'] = '';
$source = $modx->getOption('source',$scriptProperties,1);

/** @var modMediaSource $source */
$modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($modx,$source);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$success = $source->createFolder($scriptProperties['name'],$scriptProperties['parent']);
if (!$success) {
    $errors = $source->getErrors();
    foreach ($errors as $k => $msg) {
        $modx->error->addField($k,$msg);
    }
    return $modx->error->failure();
}
return $modx->error->success();

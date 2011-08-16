<?php
/**
 * Gets the contents of a file
 *
 * @param string $file The absolute path of the file
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');
/* format filename */
$file = rawurldecode($scriptProperties['file']);

/** @var modMediaSource $source */
$modx->loadClass('modMediaSource');
$source = modMediaSource::getDefaultSource($modx);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$fileArray = $source->getFile($file);

if (empty($fileArray)) {
    $msg = '';
    $errors = $source->getErrors();
    foreach ($errors as $k => $msg) {
        $modx->error->addField($k,$msg);
    }
    return $modx->error->failure($msg);
}
return $modx->error->success('',$fileArray);

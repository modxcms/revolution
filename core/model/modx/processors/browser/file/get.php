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
$source = $modx->getOption('source',$scriptProperties,1);

/** @var modMediaSource $source */
$modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($modx,$source);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$fileArray = $source->getObjectContents($file);

if (empty($fileArray)) {
    $msg = '';
    $errors = $source->getErrors();
    foreach ($errors as $k => $msg) {
        $modx->error->addField($k,$msg);
    }
    return $modx->error->failure($msg);
}
return $modx->error->success('',$fileArray);

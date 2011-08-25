<?php
/**
 * Updates a file.
 *
 * @param string $file The absolute path of the file
 * @param string $name Will rename the file if different
 * @param string $content The new content of the file
 *
 * @var modX $modx
 * @var array $scriptProperties
 * 
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_update')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

/* get base paths and sanitize incoming paths */
$filePath = rawurldecode($scriptProperties['file']);

/** @var modMediaSource $source */
$modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($modx);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$path = $source->updateFile($scriptProperties['file'],$scriptProperties['content']);

if (empty($path)) {
    $msg = '';
    $errors = $source->getErrors();
    foreach ($errors as $k => $msg) {
        $modx->error->addField($k,$msg);
    }
    return $modx->error->failure($msg);
}
return $modx->error->success('',array(
    'file' => $path,
));

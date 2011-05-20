<?php
/**
 * Updates a file.
 *
 * @param string $file The absolute path of the file
 * @param string $name Will rename the file if different
 * @param string $content The new content of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_update')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

/* get base paths and sanitize incoming paths */
$filename = rawurldecode($scriptProperties['file']);

/* get working context */
$wctx = isset($scriptProperties['wctx']) && !empty($scriptProperties['wctx']) ? $scriptProperties['wctx'] : '';
if (!empty($wctx)) {
    $workingContext = $modx->getContext($wctx);
    if (!$workingContext) {
        return $modx->error->failure($modx->error->failure($modx->lexicon('permission_denied')));
    }
} else {
    $workingContext =& $modx->context;
}

$modx->getService('fileHandler','modFileHandler', '', array('context' => $workingContext->get('key')));

/* create modFile object */
$root = $modx->fileHandler->getBasePath(false);
if ($workingContext->getOption('filemanager_path_relative',true)) {
    $root = $workingContext->getOption('base_path','').$root;
}
$fullPath = $root.ltrim($filename,'/');
$file = $modx->fileHandler->make($fullPath);

/* verify file exists */
if (!$file->exists()) return $modx->error->failure($modx->lexicon('file_err_nf').': '.$scriptProperties['file']);

/* write file */
$file->setContent($scriptProperties['content']);
$file->save();

$modx->logManagerAction('file_update','',$file->getPath());

return $modx->error->success('',array(
    'file' => rawurlencode($file->getPath()),
));

<?php
/**
 * Gets the contents of a file
 *
 * @param string $file The absolute path of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

/* format filename */
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
$root = $modx->getBasePath(false);
if ($workingContext->getOption('filemanager_path_relative',true)) {
    $root = $workingContext->getOption('base_path','').$root;
}

$modx->getService('fileHandler','modFileHandler', '', array('context' => $workingContext->get('key')));
$file = $modx->fileHandler->make($root.$filename);

if (!$file->exists()) return $modx->error->failure($modx->lexicon('file_err_nf'));
if (!$file->isReadable()) {
    return $modx->error->failure($modx->lexicon('file_err_perms'));
}
$imagesExts = array('jpg','jpeg','png','gif','ico');
$fileExtension = pathinfo($filename,PATHINFO_EXTENSION);

$fa = array(
    'name' => $file->getPath(),
    'size' => $file->getSize(),
    'last_accessed' => $file->getLastAccessed(),
    'last_modified' => $file->getLastModified(),
    'content' => $file->getContents(),
    'image' => in_array($fileExtension,$imagesExts) ? true : false,
);

return $modx->error->success('',$fa);

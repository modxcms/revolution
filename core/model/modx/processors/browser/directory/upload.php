<?php
/**
 * Upload files to a directory
 *
 * @param string $dir The target directory
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
if (!$modx->hasPermission('file_upload')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['dir'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));

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
$root = $modx->fileHandler->getBasePath(false);
if ($workingContext->getOption('filemanager_path_relative',true)) {
    $root = $workingContext->getOption('base_path','').$root;
}

$directory = $modx->fileHandler->make($root.$scriptProperties['dir']);

/* verify target path is a directory and writable */
if (!($directory instanceof modDirectory)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!($directory->isReadable()) || !$directory->isWritable()) {
    return $modx->error->failure($modx->lexicon('file_folder_err_perms_upload'));
}

$modx->context->prepare();
$allowedFileTypes = explode(',',$modx->getOption('upload_files'));
$allowedFileTypes = array_merge(explode(',',$modx->getOption('upload_images')),explode(',',$modx->getOption('upload_media')),explode(',',$modx->getOption('upload_flash')),$allowedFileTypes);
$allowedFileTypes = array_unique($allowedFileTypes);
$maxFileSize = $modx->getOption('upload_maxsize',1048576);

/* loop through each file and upload */
foreach ($_FILES as $file) {
    if ($file['error'] != 0) continue;
    if (empty($file['name'])) continue;

    $ext = @pathinfo($file['name'],PATHINFO_EXTENSION);

    if (empty($ext) || !in_array($ext,$allowedFileTypes)) {
        return $modx->error->failure($modx->lexicon('file_err_ext_not_allowed',array(
            'ext' => $ext,
        )));
    }
    $size = @filesize($file['tmp_name']);

    if ($size > $maxFileSize) {
        return $modx->error->failure($modx->lexicon('file_err_too_large',array(
            'size' => $size,
            'allowed' => $maxFileSize,
        )));
    }


    $newPath = $modx->fileHandler->sanitizePath($file['name']);
    $newPath = $directory->getPath().$newPath;

    if (!@move_uploaded_file($file['tmp_name'],$newPath)) {
        return $modx->error->failure($modx->lexicon('file_err_upload'));
    }
}

/* invoke event */
$modx->invokeEvent('OnFileManagerUpload',array(
    'files' => &$_FILES,
    'directory' => &$directory,
));

$modx->logManagerAction('file_upload','',$directory->getPath());

return $modx->error->success();

<?php
/**
 * Removes a file.
 *
 * @param string $file The name of the file.
 * @param boolean $prependPath If true, will prepend the rb_base_dir to the file
 * name.
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_remove')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['file'])) return $modx->error->failure($modx->lexicon('file_err_ns'));


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

/* get base paths and sanitize incoming paths */
$dir = $modx->fileHandler->sanitizePath($scriptProperties['file']);
if (empty($scriptProperties['basePath'])) {
    $root = $modx->fileHandler->getBasePath();
    if ($workingContext->getOption('filemanager_path_relative',true)) {
        $root = $workingContext->getOption('base_path','').$root;
    }
} else {
    $root = $scriptProperties['basePath'];
    if (!empty($scriptProperties['basePathRelative'])) {
        $root = $workingContext->getOption('base_path').$root;
    }
}
$fullPath = $root.ltrim($dir,'/');
if (!file_exists($fullPath)) return $modx->error->failure($modx->lexicon('file_folder_err_ns').': '.$fullPath);

$file = $modx->fileHandler->make($fullPath);

/* verify file exists and is writable */
if (!$file->exists()) {
    return $modx->error->failure($modx->lexicon('file_err_nf').': '.$file->getPath());
} else if (!$file->isReadable() || !$file->isWritable()) {
    return $modx->error->failure($modx->lexicon('file_err_perms_remove'));
} else if (!($file instanceof modFile)) {
    return $modx->error->failure($modx->lexicon('file_err_invalid'));
}

/* remove file */
if (!$file->remove()) {
    return $modx->error->failure($modx->lexicon('file_err_remove'));
}

/* log manager action */
$modx->logManagerAction('file_remove','',$file->getPath());

return $modx->error->success('',array(
    'path' => $file->getPath(),
));

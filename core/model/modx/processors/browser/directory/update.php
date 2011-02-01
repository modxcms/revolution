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


/* instantiate modDirectory object */
$oldDirectory = $modx->fileHandler->make($root.$scriptProperties['dir']);

/* make sure is a directory and writable */
if (!($oldDirectory instanceof modDirectory)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!$oldDirectory->isReadable() || !$oldDirectory->isWritable()) {
	return $modx->error->failure($modx->lexicon('file_folder_err_perms'));
}

/* sanitize new path */
$newPath = $modx->fileHandler->sanitizePath($scriptProperties['name']);
$newPath = $modx->fileHandler->postfixSlash($newPath);

/* rename the dir */
if (!$oldDirectory->rename($newPath)) {
    return $modx->error->failure($modx->lexicon('file_folder_err_rename'));
}

$modx->logManagerAction('directory_rename','',$oldDirectory->getPath());

return $modx->error->success();

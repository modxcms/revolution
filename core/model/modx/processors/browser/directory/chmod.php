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
$root = $modx->fileHandler->getBasePath(false);
if ($workingContext->getOption('filemanager_path_relative',true)) {
    $root = $workingContext->getOption('base_path','').$root;
}
$directoryPath = $modx->fileHandler->sanitizePath($scriptProperties['dir']);
$directoryPath = $modx->fileHandler->postfixSlash($directoryPath);
$directoryPath = $root.$directoryPath;
if (!is_dir($directoryPath)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));

$directory = $modx->fileHandler->make($directoryPath);
if (!($directory instanceof modDirectory)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!$directory->isReadable() || !$directory->isWritable()) {
    return $modx->error->failure($modx->lexicon('file_folder_err_perms'));
}
$octalPerms = $scriptProperties['mode'];
if (!$directory->chmod($octalPerms)) {
    return $modx->error->failure($modx->lexicon('file_err_chmod'));
}

$modx->logManagerAction('directory_chmod','',$directoryPath);

return $modx->error->success();

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

/* get base paths and sanitize incoming paths */
$modx->getService('fileHandler','modFileHandler');
$root = $modx->fileHandler->getBasePath(false);
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
<?php
/**
 * Create a directory.
 *
 * @param string $name The name of the directory to create
 * @param string $parent The parent directory
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
if (!$modx->hasPermission('directory_create')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['name'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));
if (empty($scriptProperties['parent'])) $scriptProperties['parent'] = '';

/* get base paths and sanitize incoming paths */
$modx->getService('fileHandler','modFileHandler');
$root = $modx->fileHandler->getBasePath();

/* create modDirectory instance for containing directory and validate */
$parentDirectory = $modx->fileHandler->make($root.$scriptProperties['parent']);
if (!($parentDirectory instanceof modDirectory)) return $modx->error->failure($modx->lexicon('file_folder_err_parent_invalid'));
if (!$parentDirectory->isReadable() || !$parentDirectory->isWritable()) {
    return $modx->error->failure($modx->lexicon('file_folder_err_perms_parent'));
}

/* create modDirectory instance for new path, validate doesnt already exist */
$path = $parentDirectory->getPath().$scriptProperties['name'];
$directory = $modx->fileHandler->make($path,array(),'modDirectory');
if ($directory->exists()) return $modx->error->failure($modx->lexicon('file_folder_err_ae'));

/* actually create the directory */
$result = $directory->create();
if ($result !== true) {
    return $modx->error->failure($modx->lexicon('file_folder_err_create').$result);
}

$modx->logManagerAction('directory_create','',$directory->getPath());

return $modx->error->success();
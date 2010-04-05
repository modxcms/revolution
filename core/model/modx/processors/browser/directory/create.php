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
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['name'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));
if (empty($scriptProperties['parent'])) $scriptProperties['parent'] = '';

/* get base paths and sanitize incoming paths */
$modx->getService('fileHandler','modFileHandler');
$root = $modx->fileHandler->getBasePath();
$parentPath = $modx->fileHandler->sanitizePath($scriptProperties['parent']);
$parentPath = $root.$parentPath;
$parentPath = $modx->fileHandler->postfixSlash($parentPath);

if (!is_dir($parentPath)) return $modx->error->failure($modx->lexicon('file_folder_err_parent_invalid'));
if (!is_readable($parentPath) || !is_writable($parentPath)) {
	return $modx->error->failure($modx->lexicon('file_folder_err_perms_parent'));
}

$newDir = $parentPath.$scriptProperties['name'];

if (file_exists($newDir)) return $modx->error->failure($modx->lexicon('file_folder_err_ae'));

$octalPerms = $modx->getOption('new_folder_permissions',null,0755);
if (!@mkdir($newDir,$octalPerms)) {
	return $modx->error->failure($modx->lexicon('file_folder_err_create'));
}

return $modx->error->success();
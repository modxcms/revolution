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
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($_POST['dir'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));

$d = isset($_POST['prependPath']) && $_POST['prependPath'] != 'null' && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');
$olddir = realpath($d.$_POST['dir']);

if (!is_dir($olddir)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($olddir) || !is_writable($olddir)) {
	return $modx->error->failure($modx->lexicon('file_folder_err_perms'));
}

$newdir = strtr(dirname($olddir).'/'.$_POST['name'],'\\','/');

if (!@rename($olddir,$newdir)) {
    return $modx->error->failure($modx->lexicon('file_folder_err_rename'));
}

return $modx->error->success();
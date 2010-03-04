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


$d = isset($scriptProperties['prependPath']) && $scriptProperties['prependPath'] != 'null' && $scriptProperties['prependPath'] != null
    ? $scriptProperties['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');
$parentdir = $d.$scriptProperties['parent'].'/';

if (!is_dir($parentdir)) return $modx->error->failure($modx->lexicon('file_folder_err_parent_invalid'));
if (!is_readable($parentdir) || !is_writable($parentdir)) {
	return $modx->error->failure($modx->lexicon('file_folder_err_perms_parent'));
}

$newdir = $parentdir.'/'.$scriptProperties['name'];

if (file_exists($newdir)) return $modx->error->failure($modx->lexicon('file_folder_err_ae'));

if (!@mkdir($newdir,0755)) {
	return $modx->error->failure($modx->lexicon('file_folder_err_create'));
}

return $modx->error->success();
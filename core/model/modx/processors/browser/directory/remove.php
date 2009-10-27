<?php
/**
 * Remove a directory
 *
 * @param string $dir The directory to remove
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
$directory = $d.$_POST['dir'];

/* in case rootVisible is true */
$directory = str_replace(array(
    'root/',
    'undefined/',
),'',$directory);

if (!is_dir($directory)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($directory) || !is_writable($directory))
	return $modx->error->failure($modx->lexicon('file_folder_err_perms_remove'));

$success = $modx->cacheManager->deleteTree($directory,array(
    'deleteTop' => true,
    'skipDirs' => false,
    'extensions' => '',
));
if (!$success) return $modx->error->failure($modx->lexicon('file_folder_err_remove'));

return $modx->error->success();
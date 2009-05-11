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
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['dir']) || $_POST['dir'] == '')
	return $modx->error->failure($modx->lexicon('file_folder_err_ns'));

$d = isset($_POST['prependPath']) && $_POST['prependPath'] != 'null' && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');
$directory = $d.$_POST['dir'];

/* in case rootVisible is true */
$directory = str_replace('root/','',$directory);
$directory = str_replace('undefined/','',$directory);

if (!is_dir($directory)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));

if (!is_readable($directory) || !is_writable($directory))
	return $modx->error->failure($modx->lexicon('file_folder_err_perms_remove'));

if (!rmdirr($directory)) return $modx->error->failure($modx->lexicon('file_folder_err_remove'));

return $modx->error->success();

function rmdirr($dr) {
	if (!is_writable($dr)) {
		if (!@chmod($dr,0777)) {
			return false;
		}
	}
	$d = dir($dr);
	if (!is_object($d)) return false;
	while (false !== ($entry = $d->read())) {
		if ($entry == '.' || $entry == '..') continue;
		$entry = $dr.'/'.$entry;
		if (is_dir($entry)) {
			if (!rrmdir($entry)) return false;
			continue;
		}
		if (!@unlink($entry)) {
			$d->close();
			return false;
		}
	}
	$d->close();
	rmdir($dr);
	return true;
}

return $modx->error->success();
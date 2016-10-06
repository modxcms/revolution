<?php
/**
 * @package modx
 * @subpackage processors.system.filesys.folder
 */

// recursive mkdir function
if (!function_exists('mkdirs')) {
	function mkdirs($strPath, $mode){
		if (is_dir($strPath)) return true;
		$pStrPath = dirname($strPath);
		if (!mkdirs($pStrPath, $mode)) return false;
		return @mkdir($strPath);
	}
}

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

// prevent subdir hack
$scriptProperties['name'] = str_replace("..\\",'',str_replace("../",'',$scriptProperties['name']));

// validate name
if (!isset($scriptProperties['name']) || $scriptProperties['name'] == '')
	return $modx->error->failure($modx->lexicon('file_folder_err_ns_name'));

if (!is_dir($scriptProperties['path']))
	return $modx->error->failure($modx->lexicon('file_folder_err_invalid_path'));

// setup vars
$nfp = $modx->getOption('new_folder_permissions');
$amode = !empty($nfp) ? octdec($modx->getOption('new_folder_permissions')) : 0777;
$new_folder = $scriptProperties['path'].'/'.$scriptProperties['name'];

if (file_exists($new_folder))
	return $modx->error->failure($modx->lexicon('file_folder_err_ae'));


// create folder
if (!mkdirs($new_folder,$amode)) {
	return $modx->error->failure($modx->lexicon('file_folder_err_create'));
} else {
	if (!@chmod($new_folder,$amode)) {
		return $modx->error->failure($modx->lexicon('file_folder_err_chmod'));
	}
}

return $modx->error->success();

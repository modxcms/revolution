<?php
/**
 * Upload files to a directory
 *
 * @param string $dir The target directory
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['dir'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));

/* get base paths and sanitize incoming paths */
$modx->getService('fileHandler','modFileHandler');
$root = $modx->fileHandler->getBasePath();
$directory = $modx->fileHandler->sanitizePath($scriptProperties['dir']);
$directory = $modx->fileHandler->postfixSlash($directory);
$directory = $root.$directory;

/* verify target path is a directory and writable */
if (!is_dir($directory)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($directory) || !is_writable($directory)) {
	return $modx->error->failure($modx->lexicon('file_folder_err_perms_upload'));
}

/* loop through each file and upload */
foreach ($_FILES as $file) {
	if ($file['error'] != 0) continue;
	if (empty($file['name'])) continue;

    $newPath = $modx->fileHandler->sanitizePath($file['name']);
    $newPath = $directory.$newPath;

	if (!@move_uploaded_file($file['tmp_name'],$newPath)) {
		return $modx->error->failure($modx->lexicon('file_err_upload'));
	}
}

/* invoke event */
$modx->invokeEvent('OnFileManagerUpload',array(
    'files' => &$_FILES,
    'directory' => $directory,
));

return $modx->error->success();
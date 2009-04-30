<?php
/**
 * Upload a photo for the user
 *
 * @deprecated
 * @package modx
 * @subpackage processors.security.user
 */
$modx->lexicon->load('user');
if (!$modx->hasPermission(array('access_permissions' => true, 'save_user' => true))) return $modx->error->failure($modx->lexicon('permission_denied'));

$files = array();
foreach($_FILES as $k => $v) {
	if ($v['tmp_name'] != '') $files[$k] = $v;
}
if (count($files) > 1) return $modx->error->failure('You may only upload one photo!');

$file = $files[0];

if ($file['error']) {
	switch($file['error']) {
		case UPLOAD_ERR_INI_SIZE: // php.ini size limit
			$e = 'Server file size limit exceeded.';
			break;
		case UPLOAD_ERR_FORM_SIZE: // MAX_FILE_SIZE from client
			$e = 'Client file size limit exceeded.';
			break;
		case UPLOAD_ERR_PARTIAL: // partial upload
			$e = 'Partial file upload.';
			break;
		case UPLOAD_ERR_NO_FILE: // no file uploaded
			$e = 'No file uploaded.';
			break;
		case UPLOAD_ERR_NO_TMP_DIR: // no temporary directory
			$e = 'Temporary directory is missing.';
			break;
		case UPLOAD_ERR_CANT_WRITE: // cannot write/permissions problem
			$e = 'Cannot write file:<br />Permissions problem or disk full.';
			break;
		case UPLOAD_ERR_EXTENSION: // upload stopped by extension
			$e = 'Upload stopped by extension.';
			break;
		default:
			$e = 'Unknown file upload error.';
			break;
	} // end of error switch
	return $modx->error->failure($e);
}

/** Decided to axe the user photo part.
$path = '/';
$name = $file['name'];
$tmp_name = $file['tmp_name'];
if (!@move_uploaded_file($file['tmp_name'], $path.'/'.$file['name'])) {
	return $modx->error->failure('Move uploaded file error. Permissions problem or disk full.');
}
**/
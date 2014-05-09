<?php
/**
 * @package modx
 * @subpackage processors.system.filesys.file
 */

if (!$modx->hasPermission('file_manager')) { return $modx->error->failure($modx->lexicon('permission_denied')); }

$file = $scriptProperties['path'].$scriptProperties['file'];
if (!is_writable($scriptProperties['path'])) {
	return $modx->error->failure($modx->lexicon('file_err_unzip_invalid_path'));
}

if (!file_exists($file)) {
	return $modx->error->failure($modx->lexicon('file_err_nf'));
}

if(!$err = @unzip(realpath($file),realpath($scriptProperties['path']))) {
	return $modx->error->failure($modx->lexicon('file_err_unzip').($err === 0 ? $modx->lexicon('file_err_unzip_missing_lib') : ''));
}

function unzip($file, $path) {
	global $modx;
	// added by Raymond
	$r = substr($path,strlen($path)-1,1);
	if ($r!="\\"||$r!="/") { $path .="/"; }
	if (!extension_loaded('zip')) {
	   if (strtoupper(substr(PHP_OS, 0,3) == 'WIN')) {
			if(!@dl('php_zip.dll')) { return 0; }
	   } else {
			if(!@dl('zip.so')) { return 0; }
	   }
	}
	// end mod
	$zip = zip_open($file);
	if ($zip) {
		$old_umask = umask(0);

		while ($zip_entry = zip_read($zip)) {
			if (zip_entry_filesize($zip_entry) > 0) {
				// str_replace must be used under windows to convert "/" into "\"	
				$complete_path = $path.str_replace('/',DIRECTORY_SEPARATOR,dirname(zip_entry_name($zip_entry)));
				$complete_name = $path.str_replace ('/',DIRECTORY_SEPARATOR,zip_entry_name($zip_entry));
				if(!file_exists($complete_path)) {
					$tmp = '';
					foreach(explode(DIRECTORY_SEPARATOR,$complete_path) AS $k) {
						$tmp .= $k.DIRECTORY_SEPARATOR;
						if(!file_exists($tmp)) {
							mkdir($tmp, octdec((!empty($modx->getOption('new_folder_permissions')) ? $modx->getOption('new_folder_permissions') : 0755)));
						}
					}
				}
				if (zip_entry_open($zip, $zip_entry, "r")) {
					$fd = fopen($complete_name, 'w');
					fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
					fclose($fd);
					zip_entry_close($zip_entry);
				}
			}
		}

		umask($old_umask);
		zip_close($zip);
		return true;
	}
	zip_close($zip);
}

return $modx->error->success();
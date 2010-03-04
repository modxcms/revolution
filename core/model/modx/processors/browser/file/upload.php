<?php
/**
 * Upload a file
 *
 * @param string $path The path to upload to
 * @param boolean $prependPath If true, will prepend rb_base_dir to the path
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['path'])) return $modx->error->failure($modx->lexicon('file_folder_err_ns'));

$d = isset($scriptProperties['prependPath']) && $scriptProperties['prependPath'] != 'null' && $scriptProperties['prependPath'] != null
    ? $scriptProperties['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');
$directory = realpath($d.$scriptProperties['path']);

$errors = array();
foreach ($_FILES as $id => $file) {
    if ($file['error'] != 0) continue;
    if (empty($file['name'])) continue;

    if (!is_dir($directory)) {
        $errors[$id] = $modx->lexicon('file_folder_err_invalid').': '.$directory;
        continue;
    }
    if (!is_readable($directory) || !is_writable($directory)) {
        $errors[$id] = $modx->lexicon('file_folder_err_perms_upload');
        continue;
    }

    $newloc = strtr($directory.'/'.$file['name'],'\\','/');

    if (!@move_uploaded_file($file['tmp_name'],$newloc)) {
        $errors[$id] = $modx->lexicon('file_err_upload');
    }
}

$o = array(
    'success' => empty($errors),
    'errors' => $errors,
);
return $modx->toJSON($o);
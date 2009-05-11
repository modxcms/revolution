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
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['path']) || $_REQUEST['path'] == '') {
    return $modx->error->failure($modx->lexicon('file_folder_err_ns'));
}

$d = isset($_POST['prependPath']) && $_POST['prependPath'] != 'null' && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');
$directory = realpath($d.$_REQUEST['path']);

$errors = array();
foreach ($_FILES as $id => $file) {
    if ($file['error'] != 0) continue;
    if ($file['name'] == '') continue;

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
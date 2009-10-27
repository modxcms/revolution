<?php
/**
 * Updates a file.
 *
 * @param string $file The absolute path of the file
 * @param string $name Will rename the file if different
 * @param string $content The new content of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

$file = rawurldecode($_REQUEST['file']);
$newname = $_POST['name'];

if (!file_exists($file)) return $modx->error->failure($modx->lexicon('file_err_nf'));

/* write file */
$f = @fopen($file,'w+');
fwrite($f,$_POST['content']);
fclose($f);

/* rename if necessary */
$filename = ltrim(strrchr($file,'/'),'/');
$path = str_replace(strrchr($file,'/'),'',$file);

if ($filename != $newname) {
    if (!@rename($path.$filename,$path.$newname)) {
        return $modx->error->failure($modx->lexicon('file_err_rename'));
    }
    $fullname = $path.$newname;
} else {
    $fullname = $file;
}


return $modx->error->success('',array(
    'file' => rawurlencode($fullname),
));
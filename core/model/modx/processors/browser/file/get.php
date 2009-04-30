<?php
/**
 * Gets the contents of a file
 *
 * @param string $file The absolute path of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

$file = rawurldecode($_POST['file']);

if (!file_exists($file)) return $modx->error->failure($modx->lexicon('file_err_nf'));

$filename = ltrim(strrchr($file,'/'),'/');

$fbuffer = @file_get_contents($file);
$time_format = '%b %d, %Y %H:%I:%S %p';

$fa = array(
    'name' => $filename,
    'size' => filesize($file),
    'last_accessed' => strftime($time_format,fileatime($file)),
    'last_modified' => strftime($time_format,filemtime($file)),
    'content' => $fbuffer,
);

return $modx->error->success('',$fa);
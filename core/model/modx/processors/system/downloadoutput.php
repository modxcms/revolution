<?php
/**
 * Output data to a file for downloading
 *
 * @package modx
 * @subpackage processors.system
 */
$cacheManager = $modx->getCacheManager();
if (!empty($_REQUEST['download'])) {
    $dl = $_REQUEST['download'];
    $dl = str_replace(array('../','..'),'',$dl);
    $dl = ltrim('/');

    $f = $modx->getOption('core_path').$dl;
    if (!is_file($f)) return '';

    $o = $cacheManager->get($dl);
    $bn = basename($f);

    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=\"{$bn}-".date('Y-m-d H:i:s').".txt\"");

    return $o;
    exit();
}

/* setup output content */
$data = $_POST['data'];
$data = strip_tags($data,'<br><span><hr><li>');
$data = str_replace(array('<li>','<hr>','<br>','<span>'),"\r\n",$data);
$data = strip_tags($data);
$o = "/*
 * MODx Console Output
 *
 * @date ".date('Y-m-d H:i:s')."
 */
".$data."
/* EOF */
";

/* setup filenames and write to file */
$file = 'export/console/output';
$fileName = $modx->getOption('core_path').$file;
if (file_exists($fileName)) $cacheManager->delete($fileName);
$s = $cacheManager->set($file,$o);


return $modx->error->success($file);
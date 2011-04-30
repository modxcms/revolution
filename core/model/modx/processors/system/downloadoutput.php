<?php
/**
 * Output data to a file for downloading
 *
 * @package modx
 * @subpackage processors.system
 */
if (!empty($scriptProperties['download'])) {
    $dl = $scriptProperties['download'];
    $dl = str_replace(array('../','..','config'),'',$dl);
    $dl = ltrim($dl,'/');

    $f = $modx->getOption('core_path').$dl;
    $o = $modx->cacheManager->get($dl);
    if (!$o) return '';

    $modx->cacheManager->delete($dl);

    $bn = basename($f);

    @session_write_close();
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=\"{$bn}-".date('Y-m-d Hi').".txt\"");

    return $o;
    exit();
}
if (empty($scriptProperties['data'])) return $modx->error->failure($modx->lexicon('error'));

/* setup output content */
$data = $scriptProperties['data'];
$data = strip_tags($data,'<br><span><hr><li>');
$data = str_replace(array('<li>','<hr>','<br>','<span>','<?php','<?','?>'),"\r\n",$data);
$data = strip_tags($data);
$o = "/*
 * MODX Console Output
 *
 * @date ".date('Y-m-d H:i:s')."
 */
".$data."
/* EOF */
";

/* setup filenames and write to file */
$file = 'export/console/output';
$fileName = $modx->getOption('core_path').$file;
if (file_exists($fileName)) $modx->cacheManager->delete($fileName);
$s = $modx->cacheManager->set($file,$o);


return $modx->error->success($file);

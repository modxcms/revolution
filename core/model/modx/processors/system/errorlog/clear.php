<?php
/**
 * Clear the error log
 *
 * @package modx
 * @subpackage processors.system.errorlog
 */
$file = $modx->cachePath.'logs/error.log';

$content = '';
if (file_exists($file)) {
    /* write file */
    $cacheManager= $modx->getCacheManager();
    $cacheManager->writeFile($file,' ');

    $content = @file_get_contents($file);
}

$la = array(
    'name' => $file,
    'log' => $content,
);
return $modx->error->success('',$la);
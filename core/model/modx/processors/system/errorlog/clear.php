<?php
/**
 * Clear the error log
 *
 * @package modx
 * @subpackage processors.system.errorlog
 */
if (!$modx->hasPermission('error_log_erase')) return $modx->error->failure($modx->lexicon('permission_denied'));
$file = $modx->getOption(xPDO::OPT_CACHE_PATH).'logs/error.log';

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
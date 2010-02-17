<?php
/**
 * Grab and output the error log
 *
 * @package modx
 * @subpackage processors.system.errorlog
 */
if (!$modx->hasPermission('error_log_view')) return $modx->error->failure($modx->lexicon('access_denied'));

$f = $modx->getOption(xPDO::OPT_CACHE_PATH).'logs/error.log';

$content = '';

if (file_exists($f)) {
    $content = @file_get_contents($f);
}

$la = array(
    'name' => $f,
    'log' => $content,
);

return $modx->error->success('',$la);
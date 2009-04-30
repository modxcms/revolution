<?php
/**
 * Grab and output the error log
 *
 * @package modx
 * @subpackage processors.system.errorlog
 */
$f = $modx->cachePath.'logs/error.log';

$content = '';

if (file_exists($f)) {
    $content = @file_get_contents($f);
}

$la = array(
    'name' => $f,
    'log' => $content,
);

return $modx->error->success('',$la);
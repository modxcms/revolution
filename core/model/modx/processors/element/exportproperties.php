<?php
/**
 * Export properties and output url to download to browser
 *
 * @package modx
 * @subpackage processors.element
 */
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','element');

$o = '';
if (empty($_REQUEST['download'])) {
    $o = $_POST['data'];

    $f = 'export.js';
    $fileName = $modx->getOption('core_path').'export/properties/'.$f;

    $cacheManager = $modx->getCacheManager();
    $s = $cacheManager->writeFile($fileName,$o);

    return $modx->error->success($f);
} else {
    $file = $_REQUEST['download'];
    $f = $modx->getOption('core_path').'export/properties/'.$file;

    if (!is_file($f)) return $o;

    $o = file_get_contents($f);

    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=\"properties.js\"");

    return $o;
}
return '';
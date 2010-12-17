<?php
/**
 * Export properties and output url to download to browser
 *
 * @package modx
 * @subpackage processors.element
 */
if (!$modx->hasPermission('view_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','element');

$o = '';
if (empty($scriptProperties['download'])) {
    $o = $scriptProperties['data'];

    $f = 'export.js';
    $fileName = $modx->getOption('core_path').'export/properties/'.$f;

    $cacheManager = $modx->getCacheManager();
    $s = $cacheManager->writeFile($fileName,$o);

    return $modx->error->success($f);
} else {
    $file = $scriptProperties['download'];
    $f = $modx->getOption('core_path').'export/properties/'.$file;

    if (empty($scriptProperties['id'])) {
        $name = 'default';
    } else {
        $propertySet = $modx->getObject('modPropertySet',$scriptProperties['id']);
        if (empty($propertySet)) {
            $name = 'unknown';
        } else {
            $name = $propertySet->get('name');
            $name = strtolower(str_replace(' ','-',$name));
        }
    }

    if (!is_file($f)) return $o;

    $o = file_get_contents($f);

    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="'.$name.'.properties.js"');

    return $o;
}
return '';
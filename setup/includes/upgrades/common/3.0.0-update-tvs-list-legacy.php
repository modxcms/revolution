<?php
/**
 * Common upgrade script for modify modTemplateVar with 'list-multiple-legacy' input type
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modTemplateVar;

/** @var modTemplateVar $legacyMultipleListTVs */
$legacyMultipleListTVs = $modx->getCollection(modTemplateVar::class, ['type' => 'list-multiple-legacy']);

foreach ($legacyMultipleListTVs as $tv) {
    $tv->set('type', 'listbox-multiple');
    $tv->save();
}

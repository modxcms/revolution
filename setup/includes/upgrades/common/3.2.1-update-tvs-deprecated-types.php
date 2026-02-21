<?php

/**
 * Common upgrade script: migrate deprecated TV input types to their fallbacks.
 * Fixes #13077 — avoids fatal errors when editing resources with TVs that use removed/deprecated types.
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modTemplateVar;

/** @var modTemplateVar $tv */
$deprecatedTvs = $modx->getCollection(modTemplateVar::class, ['type' => 'textareamini']);

foreach ($deprecatedTvs as $tv) {
    $tv->set('type', 'textarea');
    $tv->save();
}

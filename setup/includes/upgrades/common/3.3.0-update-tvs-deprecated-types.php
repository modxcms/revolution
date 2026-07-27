<?php
/**
 * Common upgrade script: migrate deprecated TV input types to their fallbacks.
 * Fixes #13077 — avoids fatal errors when editing resources with TVs that use removed/deprecated types.
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modTemplateVar;

foreach (modTemplateVar::getDeprecatedInputTypes() as $fromType => $toType) {
    $deprecatedTvs = $modx->getCollection(modTemplateVar::class, ['type' => $fromType]);
    foreach ($deprecatedTvs as $tv) {
        $tv->set('type', $toType);
        $tv->save();
    }
}

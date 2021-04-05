<?php

/**
 * Update the URL base_help_url
 */

use MODX\Revolution\modSystemSetting;

/** @var modSystemSetting $base_help_url */

$baseHelpUrl = $modx->getObject(modSystemSetting::class, [
    'key' => 'base_help_url',
    'value' => '//docs.modx.com/display/revolution20/',
]);

if ($baseHelpUrl) {
    $baseHelpUrl->set('value', '//docs.modx.com/help/');
    $baseHelpUrl->save();
}

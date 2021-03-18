<?php
/**
* Update the URL base_help_url
*/
use MODX\Revolution\modSystemSetting;

/** @var modSystemSetting $base_help_url */
$base_help_url = $modx->getObject(modSystemSetting::class, [
    'key' => 'base_help_url',
    'value' => '//docs.modx.com/display/revolution20/',
]);
if ($base_help_url) {
    $base_help_url->set('value', '//docs.modx.com/3.x/en/index');
    $base_help_url->save();
}

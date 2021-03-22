<?php
/**
 * Update the URL base_help_url
 */

/** @var modSystemSetting $base_help_url */
$base_help_url = $modx->getObject('modSystemSetting', array(
    'key' => 'base_help_url',
    'value' => '//docs.modx.com/display/revolution20/',
));
if ($base_help_url) {
    $base_help_url->set('value', '//docs.modx.com/help/');
    $base_help_url->save();
}

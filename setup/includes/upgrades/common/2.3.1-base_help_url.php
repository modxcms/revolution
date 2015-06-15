<?php
/**
 * Update the base_help_url to be protocol relative, e.g. // vs http://
 */

/** @var modSystemSetting $base_help_url */
$base_help_url = $modx->getObject('modSystemSetting', array('key' => 'base_help_url'));
if ($base_help_url && strpos($base_help_url->get('value'), 'http://rtfm.modx.com') === 0) {
    $base_help_url->set('value', str_replace('http://rtfm.modx.com', '//rtfm.modx.com', $base_help_url->get('value')));
    $base_help_url->save();
}

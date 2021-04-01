<?php
/**
 * Update the URL welcome_screen_url
 */
use MODX\Revolution\modSystemSetting;

/** @var modSystemSetting $welcome_screen_url */
$welcome_screen_url = $modx->getObject(modSystemSetting::class, [
    'key' => 'welcome_screen_url',
    'value' => '//misc.modx.com/revolution/welcome.27.html',
]);
if ($welcome_screen_url) {
    $welcome_screen_url->set('value', '//misc.modx.com/revolution/welcome.30.html');
    $welcome_screen_url->save();
}

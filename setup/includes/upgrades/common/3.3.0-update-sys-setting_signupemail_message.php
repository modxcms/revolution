<?php

/**
 * Wrap [[+surl]] in signupemail_message so mail clients do not autolink
 * trailing punctuation into the manager URL.
 *
 * @see https://github.com/modxcms/revolution/issues/15209
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

use MODX\Revolution\modSystemSetting;

/** @var modSystemSetting|null $setting */
$setting = $modx->getObject(modSystemSetting::class, [
    'key' => 'signupemail_message',
]);
if (!$setting instanceof modSystemSetting) {
    return;
}

$value = $setting->get('value');
if (!is_string($value) || $value === '' || strpos($value, '[[+surl]]') === false) {
    return;
}

if (
    stripos($value, 'href="[[+surl]]"') !== false
    || stripos($value, "href='[[+surl]]'") !== false
) {
    return;
}

$link = '<a href="[[+surl]]">[[+surl]]</a>';
$token = "\0SURL_LINK\0";

$updated = str_replace('（[[+surl]]）', $token, $value);
$updated = str_replace('([[+surl]])', $token, $updated);
$updated = str_replace('[[+surl]]', $token, $updated);
$updated = str_replace($token, $link, $updated);

if ($updated === $value) {
    return;
}

$setting->set('value', $updated);
$setting->save();

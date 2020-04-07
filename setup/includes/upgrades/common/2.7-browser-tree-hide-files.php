<?php
/**
 * Common upgrade script for enabling modx_browser_tree_hide_files System Setting
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modSystemSetting;

$object = $modx->getObject(modSystemSetting::class, ['key' => 'modx_browser_tree_hide_files', 'value:!=' => '1'], false);
if ($object) {
    $object->set('value', true);
    $object->save();
}

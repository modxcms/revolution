<?php
/**
 * Common upgrade script for enabling modx_browser_tree_hide_files System Setting
 *
 * @var modX $modx
 * @package setup
 */
if ($object = $modx->getObject('modSystemSetting', array('key' => 'modx_browser_tree_hide_files', 'value' => 0), false)) {
    $object->set('value', 1);
    $object->save();
}
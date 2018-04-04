<?php
/**
 * Common upgrade script for enabling modx_browser_tree_hide_files System Setting
 *
 * @var modX $modx
 * @package setup
 */
$object = $modx->getObject('modSystemSetting', array('key' => 'modx_browser_tree_hide_files', 'value:!=' => '1'), false);
if ($object) {
    $object->set('value', true);
    $object->save();
}

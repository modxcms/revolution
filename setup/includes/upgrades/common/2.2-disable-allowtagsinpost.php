<?php
/**
 * Common upgrade script for disabling allow_tags_in_post System Setting
 *
 * See issue #9080.
 *
 * @var modX $modx
 * @package setup
 */
if ($object = $modx->getObject('modSystemSetting', array('key' => 'allow_tags_in_post', 'value' => '1'), false)) {
    $object->set('value', '0');
    $object->save();
}
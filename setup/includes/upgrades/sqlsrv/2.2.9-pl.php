<?php
/**
 * Specific upgrades for Revolution 2.2.9-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* Remove context_key from forward_merge_excludes System Setting */
$object = $modx->getObject('modSystemSetting', array('key' => 'forward_merge_excludes'));
if ($object) {
    $value = $object->get('value');
    $exploded = explode(',', $value);
    $exploded = array_diff($exploded, array('context_key'));
    $object->set('value', implode(',', $exploded));
    $object->save();
}

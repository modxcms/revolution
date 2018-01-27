<?php
/**
 * Update various settings
 *
 * @var modX $modx
 * @package setup
 */

/* update cache handlers using xPDOFileCache or cache.xPDOFileCache */
$collection = $modx->getCollection('modSystemSetting', array('value' => 'xPDOFileCache', 'OR:value:=' => 'cache.xPDOFileCache'));
/** @var modSystemSetting $object */
foreach ($collection as $object) {
    $object->set('value', 'xPDO\Cache\xPDOFileCache');
    $object->save();
}
$collection = $modx->getCollection('modContextSetting', array('value' => 'xPDOFileCache', 'OR:value:=' => 'cache.xPDOFileCache'));
/** @var modContextSetting $object */
foreach ($collection as $object) {
    $object->set('value', 'xPDO\Cache\xPDOFileCache');
    $object->save();
}

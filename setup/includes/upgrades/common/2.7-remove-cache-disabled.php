<?php
/**
 * Common upgrade script for removing cache_disabled System Setting
 *
 * @var modX $modx
 * @package setup
 */
$modx->removeObject('modSystemSetting', array('key' => 'cache_disabled'));

<?php
/**
 * Common upgrade script for removing cache_disabled System Setting
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modSystemSetting;

$modx->removeObject(modSystemSetting::class, array('key' => 'cache_disabled'));

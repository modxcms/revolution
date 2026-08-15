<?php

/**
 * Remove cache_format from system settings.
 *
 * Changing this setting in the manager crashes the site: system settings are
 * loaded from cache, so a new format cannot read the old cache files. Override
 * via $config_options['cache_format'] in core/config/config.inc.php instead
 * (and clear the cache directory when changing it).
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modSystemSetting;

$modx->removeObject(modSystemSetting::class, ['key' => 'cache_format']);

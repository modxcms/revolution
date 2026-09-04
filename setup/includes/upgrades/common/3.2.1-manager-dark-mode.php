<?php

/**
 * Add manager_dark_mode_default system setting for Manager dark theme
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

use MODX\Revolution\modSystemSetting;

$setting = $modx->getObject(modSystemSetting::class, ['key' => 'manager_dark_mode_default']);
if (!$setting) {
    $setting = $modx->newObject(modSystemSetting::class);
    $setting->fromArray([
        'key' => 'manager_dark_mode_default',
        'value' => 'light',
        'xtype' => 'modx-combo-manager-dark-mode',
        'namespace' => 'core',
        'area' => 'manager',
        'editedon' => null,
    ], '', true, true);
    $setting->save();
}

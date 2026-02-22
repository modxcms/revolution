<?php

/**
 * Add missing resource default system settings (issue #14727)
 *
 * @var modX $modx
 * @var modInstallVersion $this
 * @package setup
 * @subpackage upgrades
 */

use MODX\Revolution\modSystemSetting;

$settingsToAdd = [
    ['key' => 'show_in_tree_default', 'value' => true, 'area' => 'site'],
    ['key' => 'hide_children_in_tree_default', 'value' => false, 'area' => 'site'],
    ['key' => 'alias_visible_default', 'value' => true, 'area' => 'site'],
    [
        'key' => 'resource_tree_num_search_results',
        'value' => 15,
        'xtype' => 'numberfield',
        'area' => 'manager',
    ],
];

foreach ($settingsToAdd as $data) {
    $existing = $modx->getObject(modSystemSetting::class, ['key' => $data['key']]);
    if (!$existing) {
        $setting = $modx->newObject(modSystemSetting::class);
        $setting->fromArray([
            'key' => $data['key'],
            'value' => $data['value'],
            'xtype' => $data['xtype'] ?? 'combo-boolean',
            'namespace' => 'core',
            'area' => $data['area'],
        ], '', true, true);
        $setting->save();
    }
}

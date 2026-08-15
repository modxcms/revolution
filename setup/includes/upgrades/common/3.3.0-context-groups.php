<?php

/**
 * Add modContextGroup table, context.context_group column, and related settings.
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modContext;
use MODX\Revolution\modContextGroup;
use MODX\Revolution\modSystemSetting;

$manager = $modx->getManager();

$manager->createObjectContainer(modContextGroup::class);

$manager->addField(modContext::class, 'context_group');
$manager->addIndex(modContext::class, 'context_group');

$settings = [
    'context_group_switch' => [
        'value' => '0',
        'xtype' => 'combo-boolean',
        'area' => 'manager',
    ],
    'context_tree_group' => [
        'value' => '1',
        'xtype' => 'combo-boolean',
        'area' => 'manager',
    ],
];

foreach ($settings as $key => $data) {
    $setting = $modx->getObject(modSystemSetting::class, ['key' => $key]);
    if (!$setting) {
        $setting = $modx->newObject(modSystemSetting::class);
        $setting->fromArray(array_merge([
            'key' => $key,
            'namespace' => 'core',
            'editedon' => null,
        ], $data), '', true, true);
        $setting->save();
    }
}

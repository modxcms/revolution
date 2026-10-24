<?php

/**
 * Ensure the manager context always starts PHP sessions.
 *
 * Fixes #16424: with system anonymous_sessions=No and no session cookie,
 * manager login could not start a session. Keep anonymous_sessions enabled
 * on the mgr context so public contexts can still disable it.
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modContext;
use MODX\Revolution\modContextSetting;

$criteria = [
    'context_key' => modContext::CONTEXT_MANAGER,
    'key' => 'anonymous_sessions',
];

$setting = $modx->getObject(modContextSetting::class, $criteria);
if (!$setting) {
    $setting = $modx->newObject(modContextSetting::class);
    $setting->fromArray([
        'context_key' => modContext::CONTEXT_MANAGER,
        'key' => 'anonymous_sessions',
        'value' => true,
        'xtype' => 'combo-boolean',
        'namespace' => 'core',
        'area' => 'session',
    ], '', true, true);
    $setting->save();
} elseif (!$modx->paramValueIsTrue(['value' => $setting->get('value')], 'value')) {
    $setting->set('value', true);
    $setting->save();
}

if ($modx->cacheManager) {
    $modx->cacheManager->refresh([
        'context_settings' => ['contexts' => [modContext::CONTEXT_MANAGER]],
    ]);
}

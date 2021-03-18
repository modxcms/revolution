<?php
/**
 * Adjust old classes to new in principal_targets system setting
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modSystemSetting;

/** @var modSystemSetting $principalTargets */
$principalTargets = $modx->getObject(modSystemSetting::class, ['key' => 'principal_targets']);
if (!$principalTargets) return;

$values = $principalTargets->value;
$values = explode(',', $values);
$values = array_map('trim', $values);

$newPrincipalTargets = [];

foreach ($values as $value) {
    if (in_array($value, ['modAccessContext', 'modAccessResourceGroup', 'modAccessCategory', 'modAccessNamespace'])) {
        $newPrincipalTargets[] = 'MODX\\Revolution\\' . $value;
        continue;
    }

    if ($value === 'sources.modAccessMediaSource') {
        $newPrincipalTargets[] = 'MODX\\Revolution\\Sources\\modAccessMediaSource';
        continue;
    }

    $newPrincipalTargets[] = $value;
}

$principalTargets->set('value', implode(',', $newPrincipalTargets));
$principalTargets->save();

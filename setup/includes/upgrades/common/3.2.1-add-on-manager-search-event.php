<?php

/**
 * Add OnManagerSearch system event
 *
 * @var modX $modx
 * @var modInstallRunner $this
 * @package setup
 * @subpackage upgrades
 */

use MODX\Revolution\modEvent;

$messageTemplate = '<p class="%s">%s</p>';

$event = $modx->getObject(modEvent::class, ['name' => 'OnManagerSearch']);
if (!$event) {
    $event = $modx->newObject(modEvent::class);
    $event->fromArray([
        'name' => 'OnManagerSearch',
        'service' => 2,
        'groupname' => 'System',
    ]);
    if ($event->save()) {
        $this->runner->addResult(
            modInstallRunner::RESULT_SUCCESS,
            sprintf($messageTemplate, 'ok', 'Added OnManagerSearch system event.')
        );
    } else {
        $this->runner->addResult(
            modInstallRunner::RESULT_ERROR,
            sprintf($messageTemplate, 'error', 'Failed to add OnManagerSearch system event.')
        );
    }
}

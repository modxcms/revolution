<?php
/**
 * Deletes an event
 *
 * @param integer $id The ID of the event to delete
 * @param boolean $cls If true, will clear all
 *
 * @package modx
 * @subpackage processors.system.event
 */
if (!$modx->hasPermission('delete_eventlog')) return $modx->error->failure($modx->lexicon('permission_denied'));

$clearall = $scriptProperties['cls'] == 1 ? true : false;

if ($clearall) {
	$events = $modx->getCollection('modEventLog');
	foreach ($events as $event) {
        $event->remove();
    }
} else {
	$event = $modx->getObject('modEventLog', $scriptProperties['id']);
	$event->remove();
}

return $modx->error->success();
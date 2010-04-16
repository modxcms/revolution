<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
if (!$modx->hasPermission('save_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin','system_events');

/* get event */
if (empty($scriptProperties['name'])) return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
$event = $modx->getObject('modEvent',array('name' => $scriptProperties['name']));
if ($event == null) return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));

/* get plugins */
$plugins = $modx->fromJSON($scriptProperties['plugins']);

/* first remove all */
$opes = $modx->getCollection('modPluginEvent',array(
    'event' => $event->get('name'),
));
foreach ($opes as $op) { $op->remove(); }
/* now add back in */
foreach ($plugins as $plugin) {
    $pluginEvent = $modx->newObject('modPluginEvent');
    $pluginEvent->set('event',$event->get('name'));
    $pluginEvent->set('pluginid',$plugin['id']);
    $pluginEvent->set('priority',$plugin['priority']);
    $pluginEvent->set('propertyset',$plugin['propertyset']);
    $pluginEvent->save();
}

return $modx->error->success();
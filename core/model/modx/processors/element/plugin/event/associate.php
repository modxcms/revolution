<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
$modx->lexicon->load('plugin','system_events');

if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get event */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
$event = $modx->getObject('modEvent',$_POST['id']);
if ($event == null) return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));

/* get plugins */
$plugins = $modx->fromJSON($_POST['plugins']);

/* first remove all */
$opes = $modx->getCollection('modPluginEvent',array(
    'evtid' => $event->get('id'),
));
foreach ($opes as $op) { $op->remove(); }
/* now add back in */
foreach ($plugins as $plugin) {
    $pluginEvent = $modx->newObject('modPluginEvent');
    $pluginEvent->set('evtid',$event->get('id'));
    $pluginEvent->set('pluginid',$plugin['id']);
    $pluginEvent->set('priority',$plugin['priority']);
    $pluginEvent->set('propertyset',$plugin['propertyset']);
    $pluginEvent->save();
}

return $modx->error->success();
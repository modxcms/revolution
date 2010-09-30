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

$eventName = $event->get('name');

foreach ($plugins as $pluginArray) {
    $pluginEvent = $modx->getObject('modPluginEvent',array(
        'event' => $eventName,
        'pluginid' => $pluginArray['id'],
    ));
    if (!empty($pluginEvent)) {
        $pluginEvent->remove();
    }
    $pluginEvent = $modx->newObject('modPluginEvent');
    $pluginEvent->set('event',$eventName);
    $pluginEvent->set('pluginid',$pluginArray['id']);
    $priority = (!empty($pluginArray['priority']) ? $pluginArray['priority'] : 0);
    $pluginEvent->set('priority',(int)$priority);
    $pluginEvent->set('propertyset',(int)(!empty($pluginArray['propertyset']) ? $pluginArray['propertyset'] : 0));

    if (!$pluginEvent->save()) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_save').print_r($pluginEvent->toArray(),true));
    }
}

return $modx->error->success();
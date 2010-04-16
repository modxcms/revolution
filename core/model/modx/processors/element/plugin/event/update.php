<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
if (!$modx->hasPermission('save_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin');

/* validation */
if (empty($scriptProperties['priority'])) $scriptProperties['priority'] = 0;
if (empty($scriptProperties['plugin']) || empty($scriptProperties['event'])) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
}

/* get plugin event */
$pluginEvent = $modx->getObject('modPluginEvent',array(
    'pluginid' => $scriptProperties['plugin'],
    'event' => $scriptProperties['event'],
));
if ($scriptProperties['enabled']) {
    /* enabling system event or editing priority */
    if (!$pluginEvent) {
        $pluginEvent = $modx->newObject('modPluginEvent');
    }
    $pluginEvent->set('pluginid',$scriptProperties['plugin']);
    $pluginEvent->set('event',$scriptProperties['event']);
    $pluginEvent->set('priority',$scriptProperties['priority']);

    if ($pluginEvent->save() == false) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_save'));
    }
} else {
    /* removing access */
    if (empty($pluginEvent)) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));
    }

    if ($pluginEvent->remove() == false) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_remove'));
    }
}

return $modx->error->success('',$pluginEvent);
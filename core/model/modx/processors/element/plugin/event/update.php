<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
if (!$modx->hasPermission('save_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin');

/* validation */
if (empty($_POST['priority'])) $_POST['priority'] = 0;
if (empty($_POST['plugin']) || empty($_POST['event'])) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
}

/* get plugin event */
$pluginEvent = $modx->getObject('modPluginEvent',array(
    'pluginid' => $_POST['plugin'],
    'evtid' => $_POST['id'],
));
if ($_POST['enabled']) {
    /* enabling system event or editing priority */
    if (!$pluginEvent) {
        $pluginEvent = $modx->newObject('modPluginEvent');
    }
    $pluginEvent->set('pluginid',$_POST['plugin']);
    $pluginEvent->set('evtid',$_POST['id']);
    $pluginEvent->set('priority',$_POST['priority']);

    if ($pluginEvent->save() == false) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_save'));
    }
} else {
    /* removing access */
    if (!$pluginEvent) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));
    }

    if ($pluginEvent->remove() == false) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_remove'));
    }
}

return $modx->error->success('',$pluginEvent);
<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
$modx->lexicon->load('plugin');

if (!$modx->hasPermission('remove')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get plugin event */
if (empty($_POST['plugin']) || empty($_POST['event'])) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
}
$pe = $modx->getObject('modPluginEvent',array(
    'pluginid' => $_POST['plugin'],
    'evtid' => $_POST['event'],
));
if ($pe == null) return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));

/* remove plugin event */
if ($pe->remove() === false) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_remove'));
}

/* invoke system event */
$modx->invokeEvent('OnPluginEventRemove',array(
    'id' => $pe->get('id'),
));

return $modx->error->success();
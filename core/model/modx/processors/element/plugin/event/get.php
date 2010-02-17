<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
if (!$modx->hasPermission('view_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin');

if (empty($_POST['plugin']) || empty($_POST['event'])) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
}
$pluginEvent = $modx->getObject('modPluginEvent',array(
    'pluginid' => $_POST['plugin'],
    'evtid' => $_POST['event'],
));
if (!$pluginEvent) return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));

return $modx->error->success('',$pluginEvent);
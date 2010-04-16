<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
if (!$modx->hasPermission('view_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin');

if (empty($scriptProperties['plugin']) || empty($scriptProperties['event'])) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
}
$pluginEvent = $modx->getObject('modPluginEvent',array(
    'pluginid' => $scriptProperties['plugin'],
    'event' => $scriptProperties['event'],
));
if (!$pluginEvent) return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));

return $modx->error->success('',$pluginEvent);
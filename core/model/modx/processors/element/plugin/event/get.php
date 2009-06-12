<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
$modx->lexicon->load('plugin');

if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (empty($_POST['plugin']) || empty($_POST['event'])) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
}
$pe = $modx->getObject('modPluginEvent',array(
    'pluginid' => $_POST['plugin'],
    'evtid' => $_POST['event'],
));
if ($pe == null) return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));

return $modx->error->success('',$pe);
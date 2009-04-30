<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
$modx->lexicon->load('plugin');

if (!isset($_POST['plugin']) || !isset($_POST['event'])) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
}
$pe = $modx->getObject('modPluginEvent',array(
    'pluginid' => $_POST['plugin'],
    'evtid' => $_POST['event'],
));
if ($pe == null) return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));
if ($pe->remove() === false) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_remove'));
}

return $modx->error->success();
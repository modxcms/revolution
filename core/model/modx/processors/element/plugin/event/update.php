<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
$modx->lexicon->load('plugin');

if ($_POST['priority'] == '') $_POST['priority'] = 0;
if (!isset($_POST['plugin']) || !isset($_POST['event'])) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
}
$pe = $modx->getObject('modPluginEvent',array(
    'pluginid' => $_POST['plugin'],
    'evtid' => $_POST['id'],
));

if ($_POST['enabled']) {
    /* enabling system event or editing priority */
    if ($pe == null) {
        $pe = $modx->newObject('modPluginEvent');
    }
    $pe->set('pluginid',$_POST['plugin']);
    $pe->set('evtid',$_POST['id']);
    $pe->set('priority',$_POST['priority']);

    if ($pe->save() == false) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_save'));
    }
} else {
    /* removing access */
    if ($pe == null) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));
    }

    if ($pe->remove() == false) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_remove'));
    }
}

return $modx->error->success();
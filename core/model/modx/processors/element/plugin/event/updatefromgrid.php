<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
$modx->lexicon->load('plugin');

if (!$modx->hasPermission('save')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* validation and data formatting */
$_DATA = $modx->fromJSON($_POST['data']);
if (empty($_DATA['priority'])) $_DATA['priority'] = 0;

/* get plugin event */
$pe = $modx->getObject('modPluginEvent',array(
    'pluginid' => $_DATA['plugin'],
    'evtid' => $_DATA['id'],
));

if ($_DATA['enabled']) {
    /* enabling system event or editing priority */
    if ($pe == null) {
        $pe = $modx->newObject('modPluginEvent');
    }
    $pe->set('pluginid',$_DATA['plugin']);
    $pe->set('evtid',$_DATA['id']);
    $pe->set('priority',$_DATA['priority']);

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
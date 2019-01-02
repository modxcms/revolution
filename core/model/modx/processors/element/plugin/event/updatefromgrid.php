<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
if (!$modx->hasPermission('save_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin');

/* validation and data formatting */
$_DATA = $modx->fromJSON($scriptProperties['data']);
if (empty($_DATA['priority'])) $_DATA['priority'] = 0;

/* get plugin event */
$pluginEvent = $modx->getObject('modPluginEvent',array(
    'pluginid' => $_DATA['plugin'],
    'event' => $_DATA['event'],
));

if ($_DATA['enabled']) {
    /* enabling system event or editing priority */
    if (!$pluginEvent) {
        $pluginEvent = $modx->newObject('modPluginEvent');
    }
    $pluginEvent->set('pluginid',$_DATA['plugin']);
    $pluginEvent->set('event',$_DATA['event']);
    $pluginEvent->set('priority',$_DATA['priority']);

    if ($pluginEvent->save() == false) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_save'));
    }
} else {
    /* removing access */
    if ($pluginEvent == null) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));
    }

    if ($pluginEvent->remove() == false) {
        return $modx->error->failure($modx->lexicon('plugin_event_err_remove'));
    }
}

return $modx->error->success('',$pluginEvent);

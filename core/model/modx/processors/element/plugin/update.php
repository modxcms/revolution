<?php
/**
 * Update a plugin.
 *
 * @param integer $id The ID of the plugin.
 * @param string $name The name of the plugin.
 * @param string $plugincode The code of the plugin.
 * @param string $description (optional) A description of the plugin.
 * @param integer $category (optional) The category for the plugin. Defaults to
 * no category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param boolean $disabled (optional) If true, the plugin does not execute.
 * @param json $events (optional) A json array of system events to associate
 * this plugin with.
 * @param json $propdata (optional) A json array of properties
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
if (!$modx->hasPermission('save_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin','category');

/* get plugin */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('plugin_err_ns'));
$plugin = $modx->getObject('modPlugin',$scriptProperties['id']);
if ($plugin == null) return $modx->error->failure($modx->lexicon('plugin_err_nf'));

if (!$plugin->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* check for locks */
if ($plugin->get('locked') && $modx->hasPermission('edit_locked') == false) {
    return $modx->error->failure($modx->lexicon('plugin_err_locked'));
}

/* Validation and data escaping */
if (empty($scriptProperties['name'])) $modx->error->addField('name',$modx->lexicon('plugin_err_ns_name'));

/* check to see if name exists */
$nameExists = $modx->getObject('modPlugin',array(
    'id:!=' => $plugin->get('id'),
    'name' => $scriptProperties['name'],
));
if ($nameExists != null) $modx->error->addField('name',$modx->lexicon('plugin_err_exists_name',array('name' => $scriptProperties['name'])));


/* category */
if (!empty($scriptProperties['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $scriptProperties['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

/* set fields */
$previousCategory = $plugin->get('category');
$plugin->fromArray($scriptProperties);
$plugin->set('locked',!empty($scriptProperties['locked']));
$plugin->set('disabled',!empty($scriptProperties['disabled']));

if (!$plugin->validate()) {
    $validator = $plugin->getValidator();
    if ($validator->hasMessages()) {
        foreach ($validator->getMessages() as $message) {
            $modx->error->addField($message['field'], $modx->lexicon($message['message']));
        }
    }
}

/* if any errors, return */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* invoke OnBeforePluginFormSave event */
$OnBeforePluginFormSave = $modx->invokeEvent('OnBeforePluginFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $plugin->get('id'),
    'plugin' => &$plugin,
));
if (is_array($OnBeforePluginFormSave)) {
    $canSave = false;
    foreach ($OnBeforePluginFormSave as $msg) {
        if (!empty($msg)) {
            $canSave .= $msg."\n";
        }
    }
} else {
    $canSave = $OnBeforePluginFormSave;
}
if (!empty($canSave)) {
    return $modx->error->failure($canSave);
}

/* save plugin */
if ($plugin->save() == false) {
    return $modx->error->failure($modx->lexicon('plugin_err_save'));
}

/* change system events */
if (isset($scriptProperties['events'])) {
    $pluginEvents = $modx->fromJSON($scriptProperties['events']);
    foreach ($pluginEvents as $id => $event) {
        $pluginEvent = $modx->getObject('modPluginEvent',array(
            'pluginid' => $plugin->get('id'),
            'event' => $event['name'],
        ));
        if ($event['enabled']) {
            if ($pluginEvent) { /* for some reason existing plugin events need to be removed before the propertyset field can be edited. doing so here. */
                $pluginEvent->remove();
            }
            $pluginEvent = $modx->newObject('modPluginEvent');
            $pluginEvent->set('pluginid',$plugin->get('id'));
            $pluginEvent->set('event',$event['name']);
            $pluginEvent->set('priority',(int)$event['priority']);
            $pluginEvent->set('propertyset',(int)$event['propertyset']);
            if (!$pluginEvent->save()) {
                $modx->log(modX::LOG_LEVEL_ERROR,'Could not save plugin event: '.print_r($pluginEvent->toArray(),true));
            }
        } elseif ($pluginEvent) {
            $pluginEvent->remove();
        }
    }
}

/* invoke OnPluginFormSave event */
$modx->invokeEvent('OnPluginFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $plugin->get('id'),
    'plugin' => &$plugin,
));

/* log manager action */
$modx->logManagerAction('plugin_update','modPlugin',$plugin->get('id'));

/* empty cache */
if (!empty($scriptProperties['clearCache'])) {
    $modx->cacheManager->refresh();
}

return $modx->error->success('', array_merge($plugin->get(array('id', 'name','description','category','locked','disabled')), array('previous_category' => $previousCategory)));

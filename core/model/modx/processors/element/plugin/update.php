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
if ($nameExists != null) $modx->error->addField('name',$modx->lexicon('plugin_err_exists_name'));


/* category */
if (!empty($scriptProperties['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $scriptProperties['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

if ($modx->error->hasError()) return $modx->error->failure();

/* set fields */
$plugin->fromArray($scriptProperties);
$plugin->set('locked',!empty($scriptProperties['locked']));
$plugin->set('disabled',!empty($scriptProperties['disabled']));

/* invoke OnBeforeTempFormSave event */
$modx->invokeEvent('OnBeforePluginFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $plugin->get('id'),
    'plugin' => &$plugin,
));

if (!$plugin->validate()) {
    $validator = $plugin->getValidator();
    if ($validator->hasMessages()) {
        foreach ($validator->getMessages() as $message) {
            $modx->error->addField($message['field'], $modx->lexicon($message['message']));
        }
    }
    if ($modx->error->hasError()) {
        return $modx->error->failure();
    }
}

/* save plugin */
if ($plugin->save() == false) {
    return $modx->error->failure($modx->lexicon('plugin_err_save'));
}

/* change system events */
if (isset($scriptProperties['events'])) {
    $_EVENTS = $modx->fromJSON($scriptProperties['events']);
    foreach ($_EVENTS as $id => $event) {
        $pe = $modx->getObject('modPluginEvent',array(
            'pluginid' => $plugin->get('id'),
            'event' => $event['name'],
        ));
        if ($event['enabled']) {
            if (!$pe) {
                $pe = $modx->newObject('modPluginEvent');
                $pe->set('pluginid',$plugin->get('id'));
                $pe->set('event',$event['name']);
            }
            $pe->set('priority',$event['priority']);
            $pe->set('propertyset',$event['propertyset']);
            $pe->save();
        } elseif ($pe) {
            $pe->remove();
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
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success('', $plugin->get(array('id', 'name','description','category','locked','disabled')));
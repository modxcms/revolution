<?php
/**
 * Creates a plugin
 *
 * @param string $name The name of the plugin
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
if (!$modx->hasPermission('new_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin','category');

/* set default name */
if (empty($scriptProperties['name'])) $scriptProperties['name'] = $modx->lexicon('plugin_untitled');

/* check to see if name already exists */
$nameExists = $modx->getObject('modPlugin',array('name' => $scriptProperties['name']));
if ($nameExists != null) $modx->error->addField('name',$modx->lexicon('plugin_err_exists_name'));

/* category */
if (!empty($scriptProperties['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $scriptProperties['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
    if (!$category->checkPolicy('add_children')) return $modx->error->failure($modx->lexicon('access_denied'));
}

if ($modx->error->hasError()) return $modx->error->failure();

$plugin = $modx->newObject('modPlugin');
$plugin->fromArray($scriptProperties);
$plugin->set('locked',!empty($scriptProperties['locked']));
$properties = null;
if (isset($scriptProperties['propdata'])) {
    $properties = $scriptProperties['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $plugin->setProperties($properties);

/* invoke OnBeforePluginFormSave event */
$modx->invokeEvent('OnBeforePluginFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => 0,
    'plugin' => &$plugin,
));


if ($plugin->save() == false) {
    return $modx->error->failure($modx->lexicon('plugin_err_create'));
}

/* change system events */
if (isset($scriptProperties['events'])) {
    $_EVENTS = $modx->fromJSON($scriptProperties['events']);
    foreach ($_EVENTS as $id => $event) {
        if (!empty($event['enabled'])) {
            $pluginEvent = $modx->getObject('modPluginEvent',array(
                'pluginid' => $plugin->get('id'),
                'event' => $event['name'],
            ));
            if ($pluginEvent == null) {
                $pluginEvent = $modx->newObject('modPluginEvent');
            }
            $pluginEvent->set('pluginid',$plugin->get('id'));
            $pluginEvent->set('event',$event['name']);
            $pluginEvent->set('priority',$event['priority']);
            $pluginEvent->save();
        } else {
            $pluginEvent = $modx->getObject('modPluginEvent',array(
                'pluginid' => $plugin->get('id'),
                'event' => $event['name'],
            ));
            if ($pluginEvent == null) continue;
            $pluginEvent->remove();
        }
    }
}

/* invoke OnPluginFormSave event */
$modx->invokeEvent('OnPluginFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => $plugin->get('id'),
    'plugin' => &$plugin,
));

/* log manager action */
$modx->logManagerAction('new_plugin','modPlugin',$plugin->get('id'));

/* empty cache */
if (!empty($scriptProperties['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}
return $modx->error->success('',$plugin->get(array_diff(array_keys($plugin->_fields), array('plugincode'))));
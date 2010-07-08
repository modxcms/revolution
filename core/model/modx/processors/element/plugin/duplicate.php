<?php
/**
 * Duplicate a plugin
 *
 * @param integer $id The ID of the plugin
 * @param string $name The new name of the duplicated plugin
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
if (!$modx->hasPermission('new_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin');

/* get old snippet */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('plugin_err_ns'));
$sourcePlugin = $modx->getObject('modPlugin',$scriptProperties['id']);
if ($sourcePlugin == null) return $modx->error->failure($modx->lexicon('plugin_err_nf'));

if (!$sourcePlugin->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* format new name */
$newName = !empty($scriptProperties['name']) ? $scriptProperties['name']
    : $modx->lexicon('duplicate_of',array(
        'name' => $sourcePlugin->get('name'),
    ));

/* check for duplicate name */
$alreadyExists = $modx->getObject('modPlugin',array(
    'name' => $newName,
));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('plugin_err_exists_name',array('name' => $newName)));

/* duplicate plugin */
$plugin = $modx->newObject('modPlugin');
$plugin->fromArray($sourcePlugin->toArray());
$plugin->set('name',$newName);

if ($plugin->save() === false) {
    $modx->error->checkValidation($plugin);
    return $modx->error->failure($modx->lexicon('plugin_err_save'));
}

/* duplicate events */
$events = $sourcePlugin->getMany('PluginEvents');
if (is_array($events) && !empty($events)) {
    foreach($events as $event) {
        $newEvent = $modx->newObject('modPluginEvent');
        $newEvent->set('pluginid',$plugin->get('id'));
        $newEvent->set('evtid',$event->get('evtid'));
        $newEvent->set('priority',$event->get('priority'));
        if ($newEvent->save() == false) {
            $plugin->remove();
            return $modx->error->failure($modx->lexicon('plugin_event_err_duplicate'));
        }
    }
}

/* log manager action */
$modx->logManagerAction('duplicate_plugin','modPlugin',$plugin->get('id'));

return $modx->error->success('',$plugin->get(array_diff(array_keys($plugin->_fields), array('plugincode'))));
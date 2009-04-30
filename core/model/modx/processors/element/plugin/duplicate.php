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
$modx->lexicon->load('plugin');

if (!$modx->hasPermission('new_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get old snippet */
$old_plugin = $modx->getObject('modPlugin',$_REQUEST['id']);
if ($old_plugin == null) return $modx->error->failure($modx->lexicon('plugin_err_not_found'));

$newname = isset($_POST['name'])
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_plugin->get('name');

/* duplicate plugin */
$plugin = $modx->newObject('modPlugin');
$plugin->fromArray($old_plugin->toArray());
$plugin->set('name',$newname);

if ($plugin->save() === false) {
    return $modx->error->failure($modx->lexicon('plugin_err_save'));
}

/* duplicate events */
$old_plugin->events = $old_plugin->getMany('modPluginEvent');
foreach($old_plugin->events as $old_event) {
	$new_event = $modx->newObject('modPluginEvent');
	$new_event->set('pluginid',$plugin->get('id'));
	$new_event->set('evtid',$old_event->get('evtid'));
	$new_event->set('priority',$old_event->get('priority'));
	if ($new_event->save() == false) {
		return $modx->error->failure($modx->lexicon('plugin_event_err_duplicate'));
    }
}

/* log manager action */
$modx->logManagerAction('duplicate_plugin','modPlugin',$plugin->get('id'));

return $modx->error->success('',$plugin->get(array_diff(array_keys($plugin->_fields), array('plugincode'))));
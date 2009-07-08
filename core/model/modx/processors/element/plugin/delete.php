<?php
/**
 * Delete a plugin.
 *
 * @param integer $id The ID of the plugin
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
$modx->lexicon->load('plugin');

if (!$modx->hasPermission('delete_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get plugin */
$plugin = $modx->getObject('modPlugin', $_POST['id']);
if ($plugin == null) return $modx->error->failure($modx->lexicon('plugin_err_not_found'));

/* remove plugin */
$plugin->remove();

/* invoke OnPluginFormDelete event */
$modx->invokeEvent('OnPluginFormDelete',array(
	'id' => $plugin->get('id'),
));

/* log manager action */
$modx->logManagerAction('plugin_delete','modPlugin',$plugin->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();
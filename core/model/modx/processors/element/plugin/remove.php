<?php
/**
 * Delete a plugin.
 *
 * @param integer $id The ID of the plugin
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
if (!$modx->hasPermission('delete_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin');

/* get plugin */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('plugin_err_ns'));
$plugin = $modx->getObject('modPlugin',$scriptProperties['id']);
if ($plugin == null) return $modx->error->failure($modx->lexicon('plugin_err_nf'));

if (!$plugin->checkPolicy('remove')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* remove plugin */
if ($plugin->remove() == false) {
    return $modx->error->failure($modx->lexicon('plugin_err_remove'));
}

/* invoke OnPluginFormDelete event */
$modx->invokeEvent('OnPluginFormDelete',array(
    'id' => $plugin->get('id'),
    'plugin' => &$plugin,
));

/* log manager action */
$modx->logManagerAction('plugin_delete','modPlugin',$plugin->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();
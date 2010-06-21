<?php
/**
 * Get a plugin
 *
 * @param integer $id The ID of the plugin
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
if (!$modx->hasPermission('view_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin');

/* get plugin */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('plugin_err_ns'));
$plugin = $modx->getObject('modPlugin', $scriptProperties['id']);
if ($plugin == null) return $modx->error->failure($modx->lexicon('plugin_err_nf'));

if (!$plugin->checkPolicy('view')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

$properties = $plugin->get('properties');
if (!is_array($properties)) $properties = array();

$data = array();
foreach ($properties as $property) {
    if (!empty($property['lexicon'])) $modx->lexicon->load($property['lexicon']);
    $data[] = array(
        $property['name'],
        $modx->lexicon($property['desc']),
        $property['type'],
        $property['options'],
        $property['value'],
        $property['lexicon'],
        false, /* overridden set to false */
    );
}

$plugin->set('data','(' . $modx->toJSON($data) . ')');

return $modx->error->success('',$plugin);
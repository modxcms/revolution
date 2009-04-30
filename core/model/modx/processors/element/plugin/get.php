<?php
/**
 * Get a plugin
 *
 * @param integer $id The ID of the plugin
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
$modx->lexicon->load('plugin');

/* get plugin */
$plugin = $modx->getObject('modPlugin', $_REQUEST['id']);
if ($plugin == null) return $modx->error->failure($modx->lexicon('plugin_err_not_found'));

$properties = $plugin->get('properties');
if (!is_array($properties)) $properties = array();

$data = array();
foreach ($properties as $property) {
    $data[] = array(
        $property['name'],
        $property['desc'],
        $property['type'],
        $property['options'],
        $property['value'],
        false, /* overridden set to false */
    );
}

$plugin->set('data','(' . $modx->toJSON($data) . ')');

return $modx->error->success('',$plugin);
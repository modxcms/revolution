<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder.vehicle
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

$vs = array();
foreach ($_SESSION['modx.pb']['vehicles'] as $index => $vehicle) {
    $vs[] = array(
        'index' => $index,
        'class_key' => $vehicle['class_key'],
        'name' => $vehicle['name'],
        'pk' => $vehicle['object'],
        'menu' => array(
            array(
                'text' => $modx->lexicon('vehicle_remove'),
                'handler' => 'this.remove.createDelegate(this,["vehicle_remove_confirm"])',
            ),
        ),
    );
}

return $this->outputArray($vs);
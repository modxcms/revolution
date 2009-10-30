<?php
/**
 * Gets properties for a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
$set = $modx->getObject('modPropertySet',$_REQUEST['id']);
if ($set == null) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

$properties = $set->get('properties');

$data = array();
if (is_array($properties)) {
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
}
return $modx->error->success('',$data);
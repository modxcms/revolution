<?php
/**
 * Gets properties for a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('view_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
$set = $modx->getObject('modPropertySet',$scriptProperties['id']);
if (empty($set)) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

$properties = $set->get('properties');
if (!is_array($properties)) $properties = array();

if (!empty($scriptProperties['element']) && !empty($scriptProperties['element_class'])) {
    $element = $modx->getObject($scriptProperties['element_class'],$scriptProperties['element']);
    if ($element) {
        $default = $element->get('properties');
    }
}


$data = array();

/* put in default properties for element */
if (isset($default) && is_array($default)) {
    foreach ($default as $property) {
        if (!empty($property['lexicon'])) $modx->lexicon->load($property['lexicon']);
        $data[$property['name']] = array(
            $property['name'],
            $modx->lexicon($property['desc']),
            $property['type'],
            $property['options'],
            $property['value'],
            $property['lexicon'],
            0,
        );
    }
}

foreach ($properties as $property) {
    $overridden = 0;
    /* if overridden, set flag */
    if (isset($data[$property['name']])) {
        $overridden = 1;
    }
    /* if completely new value, unique to set */
    if (!isset($data[$property['name']]) && isset($scriptProperties['element'])) {
        $overridden = 2;
    }

    $data[$property['name']] = array(
        $property['name'],
        $property['desc'],
        $property['type'],
        $property['options'],
        $property['value'],
        $property['lexicon'],
        $overridden,
    );
}


/* reformat data array for store */
$props = array();
foreach ($data as $key => $d) {
    $props[] = $d;
}

return $modx->error->success('',$props);
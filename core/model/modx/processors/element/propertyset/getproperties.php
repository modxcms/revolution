<?php
/**
 * Gets properties for a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('view_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
/** @var modPropertySet $set */
$set = $modx->getObject('modPropertySet',$scriptProperties['id']);
if (empty($set)) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

$properties = $set->get('properties');
if (!is_array($properties)) $properties = array();

if (!empty($scriptProperties['element']) && !empty($scriptProperties['element_class'])) {
    /** @var modElement $element */
    $element = $modx->getObject($scriptProperties['element_class'],$scriptProperties['element']);
    if ($element) {
        $default = $element->get('properties');
    }
}

$data = array();

/* put in default properties for element */
if (isset($default) && is_array($default)) {
    foreach ($default as $property) {
        $data[$property['name']] = array(
            $property['name'],
            $property['desc'],
            !empty($property['type']) ? $property['type'] : 'textfield',
            !empty($property['options']) ? $property['options'] : array(),
            $property['value'],
            !empty($property['lexicon']) ? $property['lexicon'] : '',
            0,
            $property['desc_trans'],
            !empty($property['area']) ? $property['area'] : '',
            !empty($property['area_trans']) ? $property['area_trans'] : '',
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
        $modx->lexicon($property['desc']),
        $property['type'],
        !empty($property['options']) ? $property['options'] : array(),
        $property['value'],
        !empty($property['lexicon']) ? $property['lexicon'] : '',
        $overridden,
        !empty($property['desc_trans']) ? $property['desc_trans'] : '',
        !empty($property['area']) ? $property['area'] : '',
        !empty($property['area_trans']) ? $property['area_trans'] : '',
    );
}


/* reformat data array for store */
$props = array();
foreach ($data as $key => $d) {
    $props[] = $d;
}

return $modx->error->success('',$props);
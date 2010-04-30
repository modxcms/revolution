<?php
/**
 * Grabs a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('view_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset');

/* if getting properties for an element as well */
if (!empty($scriptProperties['elementId']) && !empty($scriptProperties['elementType'])) {
    $element = $modx->getObject($scriptProperties['elementType'],$scriptProperties['elementId']);
    if ($element) {
        $default = $element->get('properties');
        if (!is_array($default)) $default = array();
    }
}

/* if no id specified */
if (!isset($scriptProperties['id']) || $scriptProperties['id'] == '') {
    return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
}
/* if grabbing a modPropertySet */
if ($scriptProperties['id'] != 0) {
    $set = $modx->getObject('modPropertySet',$scriptProperties['id']);

} elseif (isset($default)) {
    /* if grabbing default properties for an element */
    $isDefault = true;
    $set = $modx->newObject('modPropertySet');
    $set->set('id',0);
    $set->set('name',$modx->lexicon('default'));
    $set->set('properties',$default);
}

if (empty($set)) {
    return $modx->error->failure($modx->lexicon('propertyset_err_nfs',array('id' => $scriptProperties['id'])));
}


/* get set properties */
$properties = $set->get('properties');
if (!is_array($properties)) $properties = array();

/* first create temporary array to store in */
$data = array();

/* put in default properties for element */
if (isset($default)) {
    foreach ($default as $property) {
        $data[$property['name']] = array(
            $property['name'],
            $property['desc'],
            $property['type'],
            $property['options'],
            $property['value'],
            $property['lexicon'],
        );
    }
}

/* now put in set properties */
foreach ($properties as $property) {
    $overridden = false;
    /* if overridden, set flag */
    if (isset($data[$property['name']]) && !isset($isDefault)) {
        $overridden = 1;
    }
    /* if completely new value, unique to set */
    if (!isset($data[$property['name']]) && !empty($scriptProperties['elementId'])) {
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
$set->set('data','(' . $modx->toJSON($props) . ')');

return $modx->error->success('',$set);
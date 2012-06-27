<?php
/**
 * Grabs a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 * @var modX $modx
 */
if (!$modx->hasPermission('view_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset');

/* if getting properties for an element as well */
if (!empty($scriptProperties['elementId']) && !empty($scriptProperties['elementType'])) {
    /** @var modElement $element */
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
    /** @var modPropertySet $set */
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
        if (!empty($property['options']) && is_array($property['options'])) {
            foreach ($property['options'] as &$option) {
                if (empty($option['text']) && !empty($option['name'])) $option['text'] = $option['name'];
                $option['text'] = !empty($property['lexicon']) ? $modx->lexicon($option['text']) : $option['text'];
            }
        }

        $data[$property['name']] = array(
            $property['name'],
            $property['desc'],
            !empty($property['type']) ? $property['type'] : 'textfield',
            !empty($property['options']) ? $property['options'] : array(),
            $property['value'],
            !empty($property['lexicon']) ? $property['lexicon'] : '',
            false,
            $property['desc_trans'],
            !empty($property['area']) ? $property['area'] : '',
            !empty($property['area_trans']) ? $property['area_trans'] : '',
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

    foreach($property['options'] as &$option) {
        if (empty($option['text']) && !empty($option['name'])) $option['text'] = $option['name'];
        $option['text'] = !empty($property['lexicon']) ? $modx->lexicon($option['text']) : $option['text'];
    }

    $data[$property['name']] = array(
        $property['name'],
        $property['desc'],
        !empty($property['type']) ? $property['type'] : 'textfield',
        !empty($property['options']) ? $property['options'] : array(),
        $property['value'],
        !empty($property['lexicon']) ? $property['lexicon'] : '',
        $overridden,
        $property['desc_trans'],
        !empty($property['area']) ? $property['area'] : '',
        !empty($property['area_trans']) ? $property['area_trans'] : '',
    );
}

/* reformat data array for store */
$props = array();
foreach ($data as $key => $d) {
    $props[] = $d;
}
$set->set('data','(' . $modx->toJSON($props) . ')');

return $modx->error->success('',$set);

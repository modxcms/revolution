<?php
/**
 * Saves a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('save_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','element');

/* unencode data */
$data = $modx->fromJSON($scriptProperties['data']);

/* get element, if necessary */
if (isset($scriptProperties['elementId']) && isset($scriptProperties['elementType'])) {
    $element = $modx->getObject($scriptProperties['elementType'],$scriptProperties['elementId']);
    $default = $element->getProperties();
}

/* if no id specified */
if (!isset($scriptProperties['id']) || $scriptProperties['id'] == '') {
    return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
}
/* if grabbing a modPropertySet */
if ($scriptProperties['id'] != 0) {
    $set = $modx->getObject('modPropertySet',$scriptProperties['id']);
    if ($set == null) {
        return $modx->error->failure($modx->lexicon('propertyset_err_nfs',array(
            'id' => $scriptProperties['id'],
        )));
    }

    /* if editing an element, unset properties from the set that are the
     * same as the default properties, to save db space
     */
    if (isset($element)) {
        foreach ($data as $k => $prop) {
            if (array_key_exists($prop['name'],$default)) {
                if ($prop['value'] == $default[$prop['name']]) {
                    unset($data[$k]);
                }
            }
        }
    }
    $set->setProperties($data);
    $set->save();

/* if setting default properties for an element */
} else {
    if (!isset($element)) return $modx->error->failure($modx->lexicon('element_err_ns'));
    if ($element == null) return $modx->error->failure($modx->lexicon('element_err_nf'));

    $element->setProperties($data);
    $element->save();
}

return $modx->error->success();
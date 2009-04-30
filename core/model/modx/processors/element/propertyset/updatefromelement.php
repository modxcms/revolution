<?php
/**
 * Saves a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
$modx->lexicon->load('propertyset','element');

/* unencode data */
$data = $modx->fromJSON($_POST['data']);

/* get element, if necessary */
if (isset($_POST['elementId']) && isset($_POST['elementType'])) {
    $element = $modx->getObject($_POST['elementType'],$_POST['elementId']);
    $default = $element->getProperties();
}

/* if no id specified */
if (!isset($_REQUEST['id']) || $_REQUEST['id'] == '') {
    return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
}
/* if grabbing a modPropertySet */
if ($_REQUEST['id'] != 0) {
    $set = $modx->getObject('modPropertySet',$_REQUEST['id']);
    if ($set == null) {
        return $modx->error->failure($modx->lexicon('propertyset_err_nfs',array(
            'id' => $_REQUEST['id'],
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
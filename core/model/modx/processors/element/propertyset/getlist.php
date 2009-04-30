<?php
/**
 * Grabs a list of property sets.
 *
 * @param integer $elementId (optional) If set, will only grab property sets for
 * that element. Will also add a 'default' property set with the element's
 * default properties.
 * @param string $elementType (optional) The class key of the prior-mentioned
 * element.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
$modx->lexicon->load('propertyset');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modPropertySet');

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if (isset($_REQUEST['limit'])) {
    $c = $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$sets = $modx->getCollection('modPropertySet',$c);
$count = $modx->getCount('modPropertySet');


if (isset($_REQUEST['showNotAssociated']) && $_REQUEST['showNotAssociated']) {
    $eps = $modx->getCollection('modElementPropertySet',array(
        'element' => $_REQUEST['elementId'],
        'element_class' => $_REQUEST['elementType'],
    ));

    foreach ($eps as $ep) {
        $psId = $ep->get('property_set');
        if (array_key_exists($psId,$sets)) {
            unset($sets[$psId]);
        }
    }
} elseif (isset($_REQUEST['showAssociated']) && $_REQUEST['showAssociated']) {
    $eps = $modx->getCollection('modElementPropertySet',array(
        'element' => $_REQUEST['elementId'],
        'element_class' => $_REQUEST['elementType'],
    ));

    $exists = array();
    foreach ($eps as $ep) {
        $psId = $ep->get('property_set');
        if (array_key_exists($psId,$sets)) {
            $exists[] = $psId;
        }
    }

    foreach ($sets as $key => $set) {
        if (!in_array($key,$exists)) {
            unset($sets[$key]);
            $count--;
        }
    }
}



$cs = array();
if (isset($_REQUEST['elementId']) && isset($_REQUEST['elementType'])) {
    $properties = array();
    $element = $modx->getObject($_POST['elementType'],$_POST['elementId']);
    if ($element) {
        $properties = $element->get('properties');
        if (!is_array($properties)) $properties = array();
    }

    if (!isset($_REQUEST['showNotAssociated']) || !$_REQUEST['showNotAssociated']) {
        $cs[] = array('id' => 0, 'name' => $modx->lexicon('default'), 'description' => '', 'properties' => $properties);
        $count++;
    }
}

foreach ($sets as $set) {
    $cs[] = $set->toArray();
}

return $this->outputArray($cs,$count);
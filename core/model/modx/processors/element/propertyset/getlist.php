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
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset');

/* setup default properties */
$isLimit = empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

$showNotAssociated = $modx->getOption('showNotAssociated',$_REQUEST,false);
$showAssociated = $modx->getOption('showAssociated',$_REQUEST,false);
$elementId = $modx->getOption('elementId',$_REQUEST,false);
$elementType = $modx->getOption('elementType',$_REQUEST,false);

/* query for sets */
$c = $modx->newQuery('modPropertySet');
$c->sortby($sort,$dir);
if ($limit) $c->limit($limit,$start);
$sets = $modx->getCollection('modPropertySet',$c);
$count = $modx->getCount('modPropertySet');

/* if showing unassociated/associated sets */
if ($showNotAssociated) {
    $elementPropertySets = $modx->getCollection('modElementPropertySet',array(
        'element' => $elementId,
        'element_class' => $elementType,
    ));

    foreach ($elementPropertySets as $elementPropertySet) {
        $psId = $elementPropertySet->get('property_set');
        if (array_key_exists($psId,$sets)) {
            unset($sets[$psId]);
        }
    }
} elseif ($showAssociated) {
    $elementPropertySets = $modx->getCollection('modElementPropertySet',array(
        'element' => $elementId,
        'element_class' => $elementType,
    ));

    $exists = array();
    foreach ($elementPropertySets as $elementPropertySet) {
        $psId = $elementPropertySet->get('property_set');
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



$list = array();
/* if limiting to an Element */
if ($elementId && $elementType) {
    $properties = array();
    $element = $modx->getObject($elementType,$elementId);
    if ($element) {
        $properties = $element->get('properties');
        if (!is_array($properties)) $properties = array();
    }

    if (empty($showNotAssociated)) {
        $list[] = array('id' => 0, 'name' => $modx->lexicon('default'), 'description' => '', 'properties' => $properties);
        $count++;
    }
}

/* iterate through sets */
foreach ($sets as $set) {
    $list[] = $set->toArray();
}

return $this->outputArray($list,$count);
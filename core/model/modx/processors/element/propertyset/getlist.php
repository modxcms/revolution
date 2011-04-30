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
if (!$modx->hasPermission('view_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$showNotAssociated = $modx->getOption('showNotAssociated',$scriptProperties,false);
$showAssociated = $modx->getOption('showAssociated',$scriptProperties,false);
$elementId = $modx->getOption('elementId',$scriptProperties,false);
$elementType = $modx->getOption('elementType',$scriptProperties,false);

/* query for sets */
$c = $modx->newQuery('modPropertySet');
$c->leftJoin('modElementPropertySet','Elements', array(
    'Elements.element_class'=> $elementType,
    'Elements.element'=> $elementId,
    'Elements.property_set = modPropertySet.id'
));
if ($showNotAssociated) {
    $c->where(array(
        'Elements.property_set' => null,
    ));
} else if ($showAssociated) {
    $c->where(array(
        'Elements.property_set:!=' => null,
    ));
}

$count = $modx->getCount('modPropertySet',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$sets = $modx->getCollection('modPropertySet',$c);

$list = array();
/* if limiting to an Element, get default properties */
if ($elementId && $elementType && empty($showNotAssociated)) {
    $properties = array();
    $element = $modx->getObject($elementType,$elementId);
    if ($element) {
        $properties = $element->get('properties');
        if (!is_array($properties)) $properties = array();
    }
    $list[] = array('id' => 0, 'name' => $modx->lexicon('default'), 'description' => '', 'properties' => $properties);
}

/* iterate through sets */
foreach ($sets as $set) {
    $list[] = $set->toArray();
}

return $this->outputArray($list,$count);
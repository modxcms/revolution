<?php
/**
 * Removes an element from a propertyset
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('delete_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','element');

if (empty($scriptProperties['element_class']) || empty($scriptProperties['element'])) {
    return $modx->error->failure($modx->lexicon('element_err_ns'));
}
$elementClass = $scriptProperties['element_class'];
$elementId = $scriptProperties['element'];

$pse = $modx->getObject('modElementPropertySet',array(
    'element' => $elementId,
    'element_class' => $elementClass,
    'property_set' => $scriptProperties['propertyset'],
));
if ($pse == null) return $modx->error->failure($modx->lexicon('propertyset_err_element_nf'));

if ($pse->remove() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_element_remove'));
}

return $modx->error->success('',$pse);
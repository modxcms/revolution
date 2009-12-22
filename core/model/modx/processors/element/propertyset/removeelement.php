<?php
/**
 * Removes an element from a propertyset
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('remove')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','element');

if (!isset($_POST['element_class']) || !isset($_POST['element'])) {
    return $modx->error->failure($modx->lexicon('element_err_ns'));
}
$elementClass = $_POST['element_class'];
$elementId = $_POST['element'];

$pse = $modx->getObject('modElementPropertySet',array(
    'element' => $elementId,
    'element_class' => $elementClass,
    'property_set' => $_POST['propertyset'],
));
if ($pse == null) return $modx->error->failure($modx->lexicon('propertyset_err_element_nf'));

if ($pse->remove() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_element_remove'));
}

return $modx->error->success('',$pse);
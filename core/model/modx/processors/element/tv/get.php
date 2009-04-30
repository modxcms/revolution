<?php
/**
 * Gets a TV
 *
 * @param integer $id The ID of the TV
 *
 * @package modx
 * @subpackage processors.element.tv
 */
$modx->lexicon->load('tv');

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('tv_err_ns'));
$tv = $modx->getObject('modTemplateVar',$_POST['id']);
if ($tv == null) {
    return $modx->error->failure(sprintf($modx->lexicon('tv_err_nfs'),$_POST['id']));
}

$tv->set('els',$tv->get('elements'));
$properties = $tv->get('properties');
if (!is_array($properties)) $properties = array();

$data = array();
foreach ($properties as $property) {
    $data[] = array(
        $property['name'],
        $property['desc'],
        $property['type'],
        $property['options'],
        $property['value'],
        false, /* overridden set to false */
    );
}

$tv->set('data','(' . $modx->toJSON($data) . ')');

return $modx->error->success('',$tv);
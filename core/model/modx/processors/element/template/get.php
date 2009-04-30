<?php
/**
 * Gets a template
 *
 * @param integer $id The ID of the template
 *
 * @package modx
 * @subpackage processors.element.template
 */
$modx->lexicon->load('template');

$template = $modx->getObject('modTemplate',$_REQUEST['id']);
if ($template == null) return $modx->error->failure($modx->lexicon('template_err_not_found'));

$properties = $template->get('properties');
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

$template->set('data','(' . $modx->toJSON($data) . ')');

return $modx->error->success('',$template);

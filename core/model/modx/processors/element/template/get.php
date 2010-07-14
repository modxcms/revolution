<?php
/**
 * Gets a template
 *
 * @param integer $id The ID of the template
 *
 * @package modx
 * @subpackage processors.element.template
 */
if (!$modx->hasPermission('view_template')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('template');

/* get template */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('template_err_ns'));
$template = $modx->getObject('modTemplate',$scriptProperties['id']);
if ($template == null) return $modx->error->failure($modx->lexicon('template_err_nf'));

if (!$template->checkPolicy('view')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

$properties = $template->get('properties');
if (!is_array($properties)) $properties = array();

$data = array();
foreach ($properties as $property) {
    if (!empty($property['lexicon'])) {
        if (strpos($property['lexicon'],':') !== false) {
            $modx->lexicon->load('en:'.$property['lexicon']);
        } else {
            $modx->lexicon->load('en:core:'.$property['lexicon']);
        }
        $modx->lexicon->load($property['lexicon']);
    }
    $data[] = array(
        $property['name'],
        $modx->lexicon($property['desc']),
        $property['type'],
        $property['options'],
        $property['value'],
        $property['lexicon'],
        false, /* overridden set to false */
    );
}

$template->set('data','(' . $modx->toJSON($data) . ')');

return $modx->error->success('',$template);

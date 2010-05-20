<?php
/**
 * Get a snippet.
 *
 * @param integer $id The ID of the snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
if (!$modx->hasPermission('view_snippet')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('snippet');

/* get snippet */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('snippet_err_ns'));
$snippet = $modx->getObject('modSnippet',$scriptProperties['id']);
if ($snippet == null) return $modx->error->failure($modx->lexicon('snippet_err_nf'));

if (!$snippet->checkPolicy('view')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

$properties = $snippet->get('properties');
if (!is_array($properties)) $properties = array();

$data = array();
foreach ($properties as $property) {
    $data[] = array(
        $property['name'],
        $property['desc'],
        $property['type'],
        $property['options'],
        $property['value'],
        $property['lexicon'],
        false, /* overridden set to false */
    );
}

$snippet->set('data','(' . $modx->toJSON($data) . ')');

return $modx->error->success('',$snippet);
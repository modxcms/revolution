<?php
/**
 * Get a snippet.
 *
 * @param integer $id The ID of the snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
$modx->lexicon->load('snippet');

if (!$modx->hasPermission('delete_snippet')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get snippet */
$snippet = $modx->getObject('modSnippet',$_REQUEST['id']);
if ($snippet == null) return $modx->error->failure($modx->lexicon('snippet_err_not_found'));

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
        false, /* overridden set to false */
    );
}

$snippet->set('data','(' . $modx->toJSON($data) . ')');

return $modx->error->success('',$snippet);
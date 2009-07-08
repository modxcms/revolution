<?php
/**
 * Gets a chunk.
 *
 * @param integer $id The ID of the chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
$modx->lexicon->load('chunk','category');

if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get chunk */
if (!isset($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('chunk_err_ns'));
$chunk = $modx->getObject('modChunk',$_REQUEST['id']);
if ($chunk == null) {
    return $modx->error->failure(sprintf($modx->lexicon('chunk_err_id_not_found'),$_REQUEST['id']));
}

$properties = $chunk->get('properties');
if (!is_array($properties)) $properties = array();

/* process data */
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

$chunk->set('data','(' . $modx->toJSON($data) . ')');

return $modx->error->success('',$chunk->toArray());
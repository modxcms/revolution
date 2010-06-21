<?php
/**
 * Gets a chunk.
 *
 * @param integer $id The ID of the chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
if (!$modx->hasPermission('view_chunk')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('chunk','category');

/* get chunk */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('chunk_err_ns'));
$chunk = $modx->getObject('modChunk',$scriptProperties['id']);
if (empty($chunk)) return $modx->error->failure($modx->lexicon('chunk_err_nfs',array('id' => $scriptProperties['id'])));

if (!$chunk->checkPolicy('view')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

$properties = $chunk->get('properties');
if (!is_array($properties)) $properties = array();

/* process data */
$data = array();
foreach ($properties as $property) {
    if (!empty($property['lexicon'])) $modx->lexicon->load($property['lexicon']);
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

$chunk->set('data','(' . $modx->toJSON($data) . ')');

return $modx->error->success('',$chunk->toArray());
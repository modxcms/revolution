<?php
/**
 * Duplicates a chunk.
 *
 * @param integer $id The chunk to duplicate
 * @param string $name The name of the new chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
if (!$modx->hasPermission('new_chunk')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('chunk');

/* Get old chunk */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('chunk_err_ns'));
$old_chunk = $modx->getObject('modChunk',$scriptProperties['id']);
if (empty($old_chunk)) return $modx->error->failure($modx->lexicon('chunk_err_nf'));

if (!$old_chunk->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* check name */
$newname = !empty($scriptProperties['name'])
    ? $scriptProperties['name']
    : $modx->lexicon('duplicate_of',array(
        'name' => $old_chunk->get('name'),
    ));

/* duplicate chunk */
$chunk = $modx->newObject('modChunk');
$chunk->fromArray($old_chunk->toArray());
$chunk->set('name',$newname);

/* save new chunk */
if ($chunk->save() === false) {
    $modx->error->checkValidation($chunk);
    return $modx->error->failure($modx->lexicon('chunk_err_duplicate'));
}

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

/* log manager action */
$modx->logManagerAction('chunk_duplicate','modChunk',$chunk->get('id'));

return $modx->error->success('',$chunk->get(array('id', 'name', 'description', 'category', 'locked')));
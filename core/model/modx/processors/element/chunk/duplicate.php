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
$modx->lexicon->load('chunk');

if (!$modx->hasPermission('new_chunk')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* Get old chunk */
$old_chunk = $modx->getObject('modChunk',$_POST['id']);
if ($old_chunk == null) return $modx->error->failure($modx->lexicon('chunk_err_not_found'));

$newname = isset($_POST['name'])
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_chunk->get('name');

/* duplicate chunk */
$chunk = $modx->newObject('modChunk');
$chunk->fromArray($old_chunk->toArray());
$chunk->set('name',$newname);

if ($chunk->save() === false) {
    $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('chunk_err_duplicate').print_r($chunk->toArray(),true));
    return $modx->error->failure($modx->lexicon('chunk_err_duplicate'));
}

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

/* log manager action */
$modx->logManagerAction('chunk_duplicate','modChunk',$chunk->get('id'));

return $modx->error->success('',$chunk->get(array('id', 'name', 'description', 'category', 'locked')));
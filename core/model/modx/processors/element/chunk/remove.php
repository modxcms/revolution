<?php
/**
 * Removes a chunk.
 *
 * @param integer $id The ID of the chunk
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
$modx->lexicon->load('chunk');

if (!$modx->hasPermission('delete_chunk')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* get the chunk */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('chunk_err_ns'));
$chunk = $modx->getObject('modChunk',$_POST['id']);
if ($chunk == null) {
    return $modx->error->failure($modx->lexicon('chunk_err_nfs',array(
        'id' => $_POST['id'],
    )));
}

/* invoke OnBeforeChunkFormDelete event */
$modx->invokeEvent('OnBeforeChunkFormDelete',array('id' => $chunk->get('id')));

/* remove chunk */
if ($chunk->remove() == false) {
    return $modx->error->failure($modx->lexicon('chunk_err_remove'));
}

/* invoke OnChunkFormDelete event */
$modx->invokeEvent('OnChunkFormDelete',array('id' => $chunk->get('id')));

/* log manager action */
$modx->logManagerAction('chunk_delete','modChunk',$chunk->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();
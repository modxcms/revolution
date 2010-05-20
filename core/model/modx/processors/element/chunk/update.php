<?php
/**
 * Updates a chunk.
 *
 * @param integer $id The ID of the chunk.
 * @param string $name The name of the chunk.
 * @param string $description (optional) The description of the chunk.
 * @param integer $category The category the chunk is assigned to.
 * @param string $snippet The code of the chunk.
 * @param boolean $locked Whether or not the chunk can only be accessed by
 * administrators.
 * @param json $propdata A json array of properties to store.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
if (!$modx->hasPermission('save_chunk')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('chunk','category');

/* make sure a name was specified */
if (empty($scriptProperties['name'])) $modx->error->addField('name',$modx->lexicon('chunk_err_ns_name'));

/* grab chunk */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('chunk_err_ns'));
$chunk = $modx->getObject('modChunk',$scriptProperties['id']);
if (empty($chunk)) return $modx->error->failure($modx->lexicon('chunk_err_nfs',array('id' => $scriptProperties['id'])));

/* check access */
if (!$chunk->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* if chunk is locked */
if ($chunk->get('locked') && $modx->hasPermission('edit_locked') == false) {
    return $modx->error->failure($modx->lexicon('chunk_err_locked'));
}

/* if changing name, but new one already exists */
$nameExists = $modx->getObject('modChunk',array(
    'id:!=' => $chunk->get('id'),
    'name' => $scriptProperties['name'],
));
if (!empty($nameExists)) {
    $modx->error->addField('name',$modx->lexicon('chunk_err_exists_name'));
}


/* category */
if (!empty($scriptProperties['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $scriptProperties['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

/* if has any errors, send back */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* invoke OnBeforeChunkFormSave event */
$modx->invokeEvent('OnBeforeChunkFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $chunk->get('id'),
    'chunk' => &$chunk,
));

/* propogate values */
$chunk->fromArray($scriptProperties);
$chunk->set('locked',!empty($scriptProperties['locked']));

/* save the chunk */
if ($chunk->save() == false) {
    return $modx->error->failure($modx->lexicon('chunk_err_save'));
}

/* invoke OnChunkFormSave event */
$modx->invokeEvent('OnChunkFormSave',array(
    'mode'  => modSystemEvent::MODE_UPD,
    'id'    => $chunk->get('id'),
    'chunk' => &$chunk,
));

/* log manager action */
$modx->logManagerAction('chunk_update','modChunk',$chunk->get('id'));

/* empty cache */
if (!empty($scriptProperties['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success('',$chunk->get(array('id', 'name', 'description', 'locked', 'category')));
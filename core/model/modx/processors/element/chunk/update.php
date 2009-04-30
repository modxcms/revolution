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
$modx->lexicon->load('chunk','category');

if (!$modx->hasPermission('save_chunk')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* make sure a name was specified */
if ($_POST['name'] == '') {
    $modx->error->addField('name',$modx->lexicon('chunk_err_not_specified_name'));
}
/* get rid of invalid chars in name */
$_POST['name'] = str_replace('>','',$_POST['name']);
$_POST['name'] = str_replace('<','',$_POST['name']);

/* grab chunk */
$chunk = $modx->getObject('modChunk',$_POST['id']);
if ($chunk == null) {
    return $modx->error->failure(sprintf($modx->lexicon('chunk_err_id_not_found'),$_POST['id']));
}

/* if chunk is locked */
if ($chunk->get('locked') && $modx->hasPermission('edit_locked') == false) {
    return $modx->error->failure($modx->lexicon('chunk_err_locked'));
}

/* if changing name, but new one already exists */
$name_exists = $modx->getObject('modChunk',array(
    'id:!=' => $chunk->get('id'),
    'name' => $_POST['name'],
));
if ($name_exists != null) {
    $modx->error->addField('name',$modx->lexicon('chunk_err_exists_name'));
}

/* if has any errors, send back */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* category */
if (isset($_POST['category'])) {
    $categoryPk = $_POST['category'];
    $c = is_numeric($categoryPk)
        ? array('id' => $categoryPk)
        : array('name' => $categoryPk);
    $category = $modx->getObject('modCategory',$c);
    if ($category == null) {
        $category = $modx->newObject('modCategory');
        if ($categoryPk == '' || $categoryPk == 'null') {
            $category->set('id',0);
        } else {
            $category->set('category',$categoryPk);
            if ($category->save() == false) {
                $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('category_error_save').print_r($category->toArray(),true));
                return $modx->error->failure($modx->lexicon('category_err_save'));
            }
        }
    }
    unset($categoryPk,$c);
}

/* invoke OnBeforeChunkFormSave event */
$modx->invokeEvent('OnBeforeChunkFormSave',array(
    'mode' => 'upd',
    'id' => $_POST['id'],
));

/* propogate values */
$chunk->fromArray($_POST);
$chunk->set('locked',isset($_POST['locked']));
$chunk->set('category',$category->get('id'));

/* set properties */
$properties = null;
if (isset($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) { $chunk->setProperties($properties); }

/* save the chunk */
if ($chunk->save() == false) {
    $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('chunk_err_save').print_r($chunk->toArray(),true));
    return $modx->error->failure($modx->lexicon('chunk_err_save'));
}

/* invoke OnChunkFormSave event */
$modx->invokeEvent('OnChunkFormSave',array(
    'mode'  => 'upd',
    'id'    => $chunk->get('id'),
));

/* log manager action */
$modx->logManagerAction('chunk_update','modChunk',$chunk->get('id'));

/* empty cache */
if (isset($_POST['clearCache']) && $_POST['clearCache']) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success();
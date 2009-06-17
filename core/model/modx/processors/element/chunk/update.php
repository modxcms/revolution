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
/* get rid of invalid chars */
$invchars = array('!','@','#','$','%','^','&','*','(',')','+','=',
    '[',']','{','}','\'','"',':',';','\\','/','<','>','?',' ',',','`','~');
$_POST['name'] = str_replace($invchars,'',$_POST['name']);

/* grab chunk */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('chunk_err_ns'));
$chunk = $modx->getObject('modChunk',$_POST['id']);
if ($chunk == null) {
    return $modx->error->failure($modx->lexicon('chunk_err_nfs',array(
        'id' => $_POST['id'],
    )));
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


/* category */
if (!empty($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

/* if has any errors, send back */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* invoke OnBeforeChunkFormSave event */
$modx->invokeEvent('OnBeforeChunkFormSave',array(
    'mode' => 'upd',
    'id' => $_POST['id'],
));

/* propogate values */
$chunk->fromArray($_POST);
$chunk->set('locked',!empty($_POST['locked']));

/* set properties */
$properties = null;
if (isset($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) { $chunk->setProperties($properties); }

/* save the chunk */
if ($chunk->save() == false) {
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
if (!empty($_POST['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success('',$chunk->get(array('id', 'name', 'description', 'locked', 'category')));
<?php
/**
 * Creates a chunk.
 *
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
$modx->lexicon->load('chunk');

if (!$modx->hasPermission('new_chunk')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* default values */
if ($_POST['name'] == '') { $_POST['name'] = $modx->lexicon('chunk_untitled'); }
/* get rid of invalid chars */
$_POST['name'] = str_replace('>','',$_POST['name']);
$_POST['name'] = str_replace('<','',$_POST['name']);

/* verify chunk with that name does not already exist */
$name_exists = $modx->getObject('modChunk',array('name' => $_POST['name']));
if ($name_exists != null) {
    return $modx->error->failure($modx->lexicon('chunk_err_exists_name'));
}

/* if has any errors, return */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* category */
if (is_numeric($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
} else {
    $category = $modx->getObject('modCategory',array('category' => $_POST['category']));
}
if ($category == null) {
	$category = $modx->newObject('modCategory');
	if ($_POST['category'] == '' || $_POST['category'] == 'null') {
		$category->set('id',0);
	} else {
		$category->set('category',$_POST['category']);
		$category->save();
	}
}

/* invoke OnBeforeChunkFormSave event */
$modx->invokeEvent('OnBeforeChunkFormSave',array(
	'mode'	=> 'new',
	'id'	=> $_POST['id'],
));

/* save the new chunk */
$chunk = $modx->newObject('modChunk', $_POST);
$chunk->fromArray($_POST);
$chunk->set('locked',isset($_POST['locked']));
$chunk->set('category',$category->get('id'));

/* set properties */
$properties = null;
if (isset($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $chunk->setProperties($properties);

if ($chunk->save() == false) {
	return $modx->error->failure($modx->lexicon('chunk_err_save'));
}

/* invoke OnChunkFormSave event */
$modx->invokeEvent('OnChunkFormSave',array(
	'mode' => 'new',
	'id'   => $chunk->get('id'),
));

/* log manager action */
$modx->logManagerAction('chunk_create','modChunk',$chunk->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success('',$chunk->get(array('id', 'name', 'description', 'locked', 'category')));
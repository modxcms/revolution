<?php
/**
 * Update a Source from the grid. Sent through JSON-encoded 'data' parameter.
 *
 * @param integer $id The ID of the Source
 * @param string $name The new name
 * @param string $description (optional) A short description
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 * 
 * @package modx
 * @subpackage processors.source
 */
if (!$modx->hasPermission('sources')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('source');

if (empty($scriptProperties['data'])) return $modx->error->failure($modx->lexicon('source_err_ns'));
$_DATA = $modx->fromJSON($scriptProperties['data']);
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('source_err_ns'));

/** @var modMediaSource $source */
$source = $modx->getObject('sources.modMediaSource',$_DATA['id']);
if (empty($source)) return $modx->error->failure($modx->lexicon('source_err_nf'));

if (!$source->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* save source */
$source->fromArray($_DATA);
if ($source->save() == false) {
    $modx->error->checkValidation($source);
    return $modx->error->failure($modx->lexicon('source_err_save'));
}

/* log manager action */
$modx->logManagerAction('source_update','sources.modMediaSource',$source->get('id'));

return $modx->error->success('',$source);
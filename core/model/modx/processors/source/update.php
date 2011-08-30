<?php
/**
 * Updates a Media Source
 *
 * @param integer $id The ID of the Source
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

/* get dashboard */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('source_err_ns'));
/** @var modMediaSource $source */
$source = $modx->getObject('sources.modMediaSource',$scriptProperties['id']);
if (empty($source)) {
    return $modx->error->failure($modx->lexicon('source_err_nf',array('id' => $scriptProperties['id'])));
}
$source->fromArray($scriptProperties,'',true,true);

if (!empty($scriptProperties['properties'])) {
    $properties = $modx->fromJSON($scriptProperties['properties']);
    $source->setProperties($properties);
}

/* save dashboard */
if ($source->save() == false) {
    return $modx->error->failure($modx->lexicon('source_err_save'));
}

/* log manager action */
$modx->logManagerAction('source_update','sources.modMediaSource',$source->get('id'));

return $modx->error->success('',$source);
<?php
/**
 * Removes a Media Source
 *
 * @param integer $id The ID of the source
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.source
 */
if (!$modx->hasPermission('source_delete')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('source');

/* get source */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('source_err_ns'));
/** @var modMediaSource $source */
$source = $modx->getObject('sources.modMediaSource',$scriptProperties['id']);
if (empty($source)) {
    return $modx->error->failure($modx->lexicon('source_err_nf',array('id' => $scriptProperties['id'])));
}

if (!$source->checkPolicy('remove')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
if ($source->get('id') == 1) return $modx->error->failure($modx->lexicon('source_err_remove_default'));

/* remove source */
if ($source->remove() == false) {
    return $modx->error->failure($modx->lexicon('source_err_remove'));
}

/* log manager action */
$modx->logManagerAction('source_delete','sources.modMediaSource',$source->get('id'));

return $modx->error->success('',$source);
<?php
/**
 * Removes multiple Media Sources
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

if (empty($scriptProperties['sources'])) {
    return $modx->error->failure($modx->lexicon('source_err_ns'));
}

$sourceIds = explode(',',$scriptProperties['sources']);

foreach ($sourceIds as $sourceId) {
    /** @var modMediaSource $source */
    $source = $modx->getObject('sources.modMediaSource',$sourceId);
    if (empty($source)) { continue; }

    if ($source->get('id') == 1) continue;
    if (!$source->checkPolicy('remove')) {
        continue;
    }

    if ($source->remove() == false) {
        $modx->log(modX::LOG_LEVEL_ERROR,$modx->lexicon('source_err_remove'));
        continue;
    }
    $modx->logManagerAction('source_remove','sources.modMediaSource',$source->get('id'));
}

return $modx->error->success();
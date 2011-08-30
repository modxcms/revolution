<?php
/**
 * Creates a Media Source
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

/* validate name field */
if (empty($scriptProperties['name'])) {
    $modx->error->addField('name',$modx->lexicon('source_err_ns_name'));
} else {
    $alreadyExists = $modx->getCount('sources.modMediaSource',array(
        'name' => $scriptProperties['name'],
    ));
    if ($alreadyExists > 0) {
        $modx->error->addField('name',$modx->lexicon('source_err_ae_name',array(
            'name' => $scriptProperties['name']
        )));
    }
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/** @var modMediaSource $source */
$source = $modx->newObject('sources.modMediaSource');
$source->fromArray($scriptProperties);

/* save source */
if ($source->save() == false) {
    return $modx->error->failure($modx->lexicon('source_err_save'));
}

/* log manager action */
$modx->logManagerAction('source_create','sources.modMediaSource',$source->get('id'));

return $modx->error->success('',$source);
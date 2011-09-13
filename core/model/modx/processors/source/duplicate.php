<?php
/**
 * Duplicates a source.
 *
 * @param integer $id The source to duplicate
 * @param string $name The name of the new source.
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

/* @var modMediaSource $oldSource */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('source_err_ns'));
$oldSource = $modx->getObject('sources.modMediaSource',$scriptProperties['id']);
if (empty($oldSource)) return $modx->error->failure($modx->lexicon('source_err_nf'));

if (!$oldSource->checkPolicy('copy')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* check name */
$newName = !empty($scriptProperties['name'])
    ? $scriptProperties['name']
    : $modx->lexicon('duplicate_of',array(
        'name' => $oldSource->get('name'),
    ));

/* @var modMediaSource $newSource */
$newSource = $modx->newObject('sources.modMediaSource');
$newSource->fromArray($oldSource->toArray());
$newSource->set('name',$newName);

if ($newSource->save() === false) {
    $modx->error->checkValidation($newSource);
    return $modx->error->failure($modx->lexicon('source_err_duplicate'));
}

/* log manager action */
$modx->logManagerAction('source_duplicate','sources.modMediaSource',$newSource->get('id'));

return $modx->error->success('',$newSource->get(array('id', 'name', 'description')));
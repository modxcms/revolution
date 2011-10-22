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
if (!$modx->hasPermission('source_save')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('source');

/* get dashboard */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('source_err_ns'));
/** @var modMediaSource $source */
$source = $modx->getObject('sources.modMediaSource',$scriptProperties['id']);
if (empty($source)) {
    return $modx->error->failure($modx->lexicon('source_err_nf',array('id' => $scriptProperties['id'])));
}

if (!$source->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$source->fromArray($scriptProperties,'',true,true);

if (!empty($scriptProperties['properties'])) {
    $properties = $modx->fromJSON($scriptProperties['properties']);
    $source->setProperties($properties);
}

/* save source */
if ($source->save() == false) {
    return $modx->error->failure($modx->lexicon('source_err_save'));
}

/* save access permissions */
if (!empty($scriptProperties['access'])) {
    $acls = $modx->getCollection('sources.modAccessMediaSource',array(
        'target' => $source->get('id'),
    ));
    /** @var modAccessMediaSource $acl */
    foreach ($acls as $acl) {
        $acl->remove();
    }

    $access = $modx->fromJSON($scriptProperties['access']);
    if (!empty($access) && is_array($access)) {
        foreach ($access as $data) {
            $acl = $modx->newObject('sources.modAccessMediaSource');
            $acl->fromArray(array(
                'target' => $source->get('id'),
                'principal_class' => $data['principal_class'],
                'principal' => $data['principal'],
                'authority' => $data['authority'],
                'policy' => $data['policy'],
                'context_key' => $data['context_key'],
            ),'',true,true);
            $acl->save();
        }
    }
}

/* log manager action */
$modx->logManagerAction('source_update','sources.modMediaSource',$source->get('id'));

return $modx->error->success('',$source);
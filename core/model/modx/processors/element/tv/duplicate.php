<?php
/**
 * Duplicate a TV
 *
 * @param integer $id The ID of the TV to duplicate
 * @param string $name The name of the new, duplicated TV
 *
 * @package modx
 * @subpackage processors.element.tv
 */
if (!$modx->hasPermission('new_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv');

/* get TV */
$sourceTv = $modx->getObject('modTemplateVar',$scriptProperties['id']);
if ($sourceTv == null) return $modx->error->failure($modx->lexicon('tv_err_nf'));

if (!$sourceTv->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

$newName = isset($scriptProperties['name'])
    ? $scriptProperties['name']
    : $modx->lexicon('duplicate_of',array('name' => $sourceTv->get('name')));

$alreadyExists = $modx->getObject('modTemplateVar',array('name' => $newName));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('tv_err_exists_name',array('name' => $newName)));

/* duplicate TV */
$tv = $modx->newObject('modTemplateVar');
$tv->fromArray($sourceTv->toArray());
$tv->set('name',$newName);

if ($tv->save() === false) {
    $modx->error->checkValidation($tv);
    return $modx->error->failure($modx->lexicon('tv_err_duplicate'));
}

$templates = $sourceTv->getMany('TemplateVarTemplates');
if (is_array($templates) && !empty($templates)) {
    foreach ($templates as $template) {
        $newTemplate = $modx->newObject('modTemplateVarTemplate');
        $newTemplate->set('tmplvarid',$tv->get('id'));
        $newTemplate->set('templateid',$template->get('templateid'));
        $newTemplate->set('rank',$template->get('rank'));
        $newTemplate->save();
    }
}
$resources = $sourceTv->getMany('TemplateVarResources');
if (is_array($resources) && !empty($resources)) {
    foreach ($resources as $resource) {
        $newResource = $modx->newObject('modTemplateVarResource');
        $newResource->set('tmplvarid',$tv->get('id'));
        $newResource->set('contentid',$resource->get('contentid'));
        $newResource->set('value',$resource->get('value'));
        $newResource->save();
    }
}
$resourceGroups = $sourceTv->getMany('TemplateVarResourceGroups');
if (is_array($resourceGroups) && !empty($resourceGroups)) {
    foreach ($resourceGroups as $resourceGroup) {
        $newResourceGroup = $modx->newObject('modTemplateVarResourceGroup');
        $newResourceGroup->set('tmplvarid',$tv->get('id'));
        $newResourceGroup->set('documentgroup',$resourceGroup->get('documentgroup'));
        $newResourceGroup->save();
    }
}

/* log manager action */
$modx->logManagerAction('tv_duplicate','modTemplateVar',$tv->get('id'));

return $modx->error->success('',$tv->get(array('id','name')));
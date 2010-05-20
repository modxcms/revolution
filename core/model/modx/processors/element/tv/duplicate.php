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
$old_tv = $modx->getObject('modTemplateVar',$scriptProperties['id']);
if ($old_tv == null) return $modx->error->failure($modx->lexicon('tv_err_nf'));

if (!$old_tv->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

$old_tv->templates = $old_tv->getMany('TemplateVarTemplates');
$old_tv->resources = $old_tv->getMany('TemplateVarResources');
$old_tv->resource_groups = $old_tv->getMany('TemplateVarResourceGroups');

$newname = isset($scriptProperties['name'])
    ? $scriptProperties['name']
    : $modx->lexicon('duplicate_of').$old_tv->get('name');

/* duplicate TV */
$tv = $modx->newObject('modTemplateVar');
$tv->fromArray($old_tv->toArray());
$tv->set('name',$newname);

if ($tv->save() === false) {
    return $modx->error->failure($modx->lexicon('tv_err_duplicate'));
}

foreach ($old_tv->templates as $old_tvt) {
    $new_template = $modx->newObject('modTemplateVarTemplate');
    $new_template->set('tmplvarid',$tv->get('id'));
    $new_template->set('templateid',$old_tvt->get('templateid'));
    $new_template->set('rank',$old_tvt->get('rank'));
    $new_template->save();
}
foreach ($old_tv->resources as $old_tvr) {
    $new_resource = $modx->newObject('modTemplateVarResource');
    $new_resource->set('tmplvarid',$tv->get('id'));
    $new_resource->set('contentid',$old_tvr->get('contentid'));
    $new_resource->set('value',$old_tvr->get('value'));
    $new_resource->save();
}
foreach ($old_tv->resource_groups as $old_tvrg) {
    $new_rgroup = $modx->newObject('modTemplateVarResourceGroup');
    $new_rgroup->set('tmplvarid',$tv->get('id'));
    $new_rgroup->set('documentgroup',$old_tvrg->get('documentgroup'));
    $new_rgroup->save();
}

/* log manager action */
$modx->logManagerAction('tv_duplicate','modTemplateVar',$tv->get('id'));

return $modx->error->success('',$tv->get(array('id')));
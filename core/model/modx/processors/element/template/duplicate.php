<?php
/**
 * Duplicate a TV.
 *
 *
 * @param integer $id The ID of the tv to duplicate.
 * @param string $name (optional) The name of the new tv. Defaults to Untitled
 * TV.
 *
 * @package modx
 * @subpackage processors.element.template
 */
if (!$modx->hasPermission('new_template')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('template');

/* get old template */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('template_err_ns'));
$sourceTemplate = $modx->getObject('modTemplate',$scriptProperties['id']);
if (!$sourceTemplate) return $modx->error->failure($modx->lexicon('template_err_nf'));

if (!$sourceTemplate->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* format new name */
$newName = !empty($scriptProperties['name']) 
    ? $scriptProperties['name']
    : $modx->lexicon('duplicate_of',array(
        'name' => $sourceTemplate->get('templatename'),
    ));


/* check for duplicate name */
$alreadyExists = $modx->getObject('modTemplate',array(
    'templatename' => $newName,
));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('template_err_exists_name',array('name' => $newName)));


/* duplicate template */
$template = $modx->newObject('modTemplate');
$template->fromArray($sourceTemplate->toArray());
$template->set('templatename',$newName);

if (!$template->save()) {
    $modx->error->checkValidation($template);
    return $modx->error->failure($modx->lexicon('template_err_duplicate'));
}

/* log manager action */
$modx->logManagerAction('template_duplicate','modTemplate',$template->get('id'));

return $modx->error->success('',$template->get(array_diff(array_keys($template->_fields), array('content'))));
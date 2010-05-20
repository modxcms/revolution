<?php
/**
 * Update a template
 *
 * @param integer $id The ID of the template
 * @param string $templatename The name of the template
 * @param string $content The code of the template.
 * @param string $description (optional) A brief description.
 * @param integer $category (optional) The category to assign to. Defaults to no
 * category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param json $propdata (optional) A json array of properties
 * @param json $tvs (optional) A json array of TVs associated to the template
 *
 * @package modx
 * @subpackage processors.element.template
 */
if (!$modx->hasPermission('save_template')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('template','category');

/* get template */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('template_err_ns'));
$template = $modx->getObject('modTemplate',$scriptProperties['id']);
if (!$template) return $modx->error->failure($modx->lexicon('template_err_not_found'));

if (!$template->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* check locked status */
if ($template->get('locked') && $modx->hasPermission('edit_locked') == false) {
    return $modx->error->failure($modx->lexicon('template_err_locked'));
}

/* Validation and data escaping */
if (empty($scriptProperties['templatename'])) $modx->error->addField('templatename',$modx->lexicon('template_err_not_specified_name'));

/* check to see if name already exists */
$alreadyExists = $modx->getObject('modTemplate',array(
    'id:!=' => $template->get('id'),
    'templatename' => $scriptProperties['templatename'],
));
if ($alreadyExists) $modx->error->addField('name',$modx->lexicon('template_err_exists_name'));

/* category */
if (!empty($scriptProperties['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $scriptProperties['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

if ($modx->error->hasError()) return $modx->error->failure();

/* set fields */
$template->fromArray($scriptProperties);
$template->set('locked',!empty($scriptProperties['locked']));

/* invoke OnBeforeTempFormSave event */
$modx->invokeEvent('OnBeforeTempFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $template->get('id'),
    'template' => &$template,
));

/* save template */
if ($template->save() === false) {
    return $modx->error->failure($modx->lexicon('template_err_save'));
}


/* change template access to tvs */
if (isset($scriptProperties['tvs'])) {
    $templateVariables = $modx->fromJSON($scriptProperties['tvs']);
    if (is_array($templateVariables)) {
        foreach ($templateVariables as $id => $tv) {
            if ($tv['access']) {
                $templateVarTemplate = $modx->getObject('modTemplateVarTemplate',array(
                    'tmplvarid' => $tv['id'],
                    'templateid' => $template->get('id'),
                ));
                if (empty($templateVarTemplate)) {
                    $templateVarTemplate = $modx->newObject('modTemplateVarTemplate');
                }
                $templateVarTemplate->set('tmplvarid',$tv['id']);
                $templateVarTemplate->set('templateid',$template->get('id'));
                $templateVarTemplate->set('rank',$tv['tv_rank']);
                $templateVarTemplate->save();
            } else {
                $templateVarTemplate = $modx->getObject('modTemplateVarTemplate',array(
                    'tmplvarid' => $tv['id'],
                    'templateid' => $template->get('id'),
                ));
                if ($templateVarTemplate && $templateVarTemplate instanceof modTemplateVarTemplate) {
                    $tvt->remove();
                }
            }
        }
    }
}


/* invoke OnTempFormSave event */
$modx->invokeEvent('OnTempFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $template->get('id'),
    'template' => &$template,
));

/* log manager action */
$modx->logManagerAction('template_update','modTemplate',$template->get('id'));

/* empty cache */
if (!empty($scriptProperties['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success('',$template->get(array_diff(array_keys($template->_fields), array('content'))));
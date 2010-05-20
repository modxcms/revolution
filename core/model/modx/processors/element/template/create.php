<?php
/**
 * Create a template
 *
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
if (!$modx->hasPermission('new_template')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('template','category');

/* set default name if necessary */
if (empty($scriptProperties['templatename'])) $scriptProperties['templatename'] = $modx->lexicon('template_untitled');

/* check to see if a template already exists with that name */
$nameExists = $modx->getObject('modTemplate',array(
    'templatename' => $scriptProperties['templatename'],
));
if ($nameExists) $modx->error->addField('templatename',$modx->lexicon('template_err_exists_name'));

/* category */
if (!empty($scriptProperties['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $scriptProperties['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
    if (!$category->checkPolicy('add_children')) return $modx->error->failure($modx->lexicon('access_denied'));
}

if ($modx->error->hasError()) return $modx->error->failure();

/* set fields */
$template = $modx->newObject('modTemplate');
$template->fromArray($scriptProperties);
$template->set('locked',!empty($scriptProperties['locked']));
$properties = null;
if (!empty($scriptProperties['propdata'])) {
    $properties = $scriptProperties['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $template->setProperties($properties);

/* invoke OnBeforeTempFormSave event */
$modx->invokeEvent('OnBeforeTempFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => 0,
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
                $templateVarTemplate->set('rank',$tv['rank']);
                $templateVarTemplate->save();
            } else {
                $templateVarTemplate = $modx->getObject('modTemplateVarTemplate',array(
                    'tmplvarid' => $tv['id'],
                    'templateid' => $template->get('id'),
                ));
                if ($templateVarTemplate && $templateVarTemplate instanceof modTemplateVarTemplate) {
                    $templateVarTemplate->remove();
                }
            }
        }
    }
}

/* invoke OnTempFormSave event */
$modx->invokeEvent('OnTempFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => $template->get('id'),
    'template' => &$template,
));

/* log manager action */
$modx->logManagerAction('template_create','modTemplate',$template->get('id'));

/* empty cache */
if (!empty($scriptProperties['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success('',$template->get(array_diff(array_keys($template->_fields), array('content'))));
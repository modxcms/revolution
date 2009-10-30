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
if (empty($_POST['templatename'])) $_POST['templatename'] = $modx->lexicon('template_untitled');

/* check to see if a template already exists with that name */
$nameExists = $modx->getObject('modTemplate',array(
    'templatename' => $_POST['templatename'],
));
if ($nameExists) $modx->error->addField('templatename',$modx->lexicon('template_err_exists_name'));

/* category */
if (!empty($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

if ($modx->error->hasError()) return $modx->error->failure();

/* set fields */
$template = $modx->newObject('modTemplate');
$template->fromArray($_POST);
$template->set('locked',!empty($_POST['locked']));
$properties = null;
if (!empty($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $template->setProperties($properties);

/* invoke OnBeforeTempFormSave event */
$modx->invokeEvent('OnBeforeTempFormSave',array(
    'mode' => 'new',
    'id' => 0,
    'template' => &$template,
));

/* save template */
if ($template->save() === false) {
    return $modx->error->failure($modx->lexicon('template_err_save'));
}


/* change template access to tvs */
if (isset($_POST['tvs'])) {
    $_TVS = $modx->fromJSON($_POST['tvs']);
    foreach ($_TVS as $id => $tv) {
        if ($tv['access']) {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv['id'],
                'templateid' => $template->get('id'),
            ));
            if ($tvt == null) {
                $tvt = $modx->newObject('modTemplateVarTemplate');
            }
            $tvt->set('tmplvarid',$tv['id']);
            $tvt->set('templateid',$template->get('id'));
            $tvt->set('rank',$tv['rank']);
            $tvt->save();
        } else {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv['id'],
                'templateid' => $template->get('id'),
            ));
            if ($tvt == null) continue;
            $tvt->remove();
        }
    }
}

/* invoke OnTempFormSave event */
$modx->invokeEvent('OnTempFormSave',array(
    'mode' => 'new',
    'id' => $template->get('id'),
    'template' => &$template,
));

/* log manager action */
$modx->logManagerAction('template_create','modTemplate',$template->get('id'));

/* empty cache */
if (!empty($_POST['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success('',$template->get(array_diff(array_keys($template->_fields), array('content'))));
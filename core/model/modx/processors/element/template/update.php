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
$modx->lexicon->load('template','category');

if (!$modx->hasPermission('save_template')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get template */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('template_err_ns'));
$template = $modx->getObject('modTemplate',$_POST['id']);
if ($template == null) return $modx->error->failure($modx->lexicon('template_err_not_found'));

/* check locked status */
if ($template->get('locked') && $modx->hasPermission('edit_locked') == false) {
    return $modx->error->failure($modx->lexicon('template_err_locked'));
}

/* Validation and data escaping */
if (empty($_POST['templatename'])) {
    $modx->error->addField('templatename',$modx->lexicon('template_err_not_specified_name'));
}

/* get rid of invalid chars */
$invchars = array('!','@','#','$','%','^','&','*','(',')','+','=',
    '[',']','{','}','\'','"',':',';','\\','/','<','>','?',' ',',','`','~');
$_POST['templatename'] = str_replace($invchars,'',$_POST['templatename']);

/* check to see if name already exists */
$name_exists = $modx->getObject('modTemplate',array(
    'id:!=' => $template->get('id'),
    'templatename' => $_POST['templatename']
));
if ($name_exists != null) $modx->error->addField('name',$modx->lexicon('template_err_exists_name'));

/* category */
if (!empty($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

if ($modx->error->hasError()) return $modx->error->failure();

/* invoke OnBeforeTempFormSave event */
$modx->invokeEvent('OnBeforeTempFormSave',array(
    'mode' => 'new',
    'id' => $template->get('id'),
));

$template->fromArray($_POST);
$template->set('locked',!empty($_POST['locked']));

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
));

/* log manager action */
$modx->logManagerAction('template_update','modTemplate',$template->get('id'));

/* empty cache */
if (!empty($_POST['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success();
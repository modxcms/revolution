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
$modx->lexicon->load('template','category');

if (!$modx->hasPermission('new_template')) return $modx->error->failure($modx->lexicon('permission_denied'));

if ($_POST['templatename'] == '') $_POST['templatename'] = $modx->lexicon('template_untitled');

/* sanity check on name */
$_POST['templatename'] = str_replace('>','',$_POST['templatename']);
$_POST['templatename'] = str_replace('<','',$_POST['templatename']);

$name_exists = $modx->getObject('modTemplate',array('templatename' => $_POST['templatename']));
if ($name_exists != null) $modx->error->addField('templatename',$modx->lexicon('template_err_exists_name'));

if ($modx->error->hasError()) return $modx->error->failure();

/* category */
if (is_numeric($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
} else {
    $category = $modx->getObject('modCategory',array('category' => $_POST['category']));
}
if ($category == null) {
    $category = $modx->newObject('modCategory');
    if ($_POST['category'] == '' || $_POST['category'] == 'null') {
        $category->set('id',0);
    } else {
        $category->set('category',$_POST['category']);
        if ($category->save() == false) {
            return $modx->error->failure($modx->lexicon('category_err_save'));
        }
    }
}

/* invoke OnBeforeTempFormSave event */
$modx->invokeEvent('OnBeforeTempFormSave',array(
    'mode' => 'new',
    'id' => 0,
));

$template = $modx->newObject('modTemplate');
$template->fromArray($_POST);
$template->set('locked', isset($_POST['locked']));
$template->set('category',$category->get('id'));
$properties = null;
if (isset($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $template->setProperties($properties);

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
$modx->logManagerAction('template_create','modTemplate',$template->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success('',$template->get(array_diff(array_keys($template->_fields), array('content'))));
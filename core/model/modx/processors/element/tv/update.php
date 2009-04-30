<?php
/**
 * Updates a TV
 *
 * @param string $name The name of the TV
 * @param string $caption (optional) A short caption for the TV.
 * @param string $description (optional) A brief description.
 * @param integer $category (optional) The category to assign to. Defaults to no
 * category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param string $els (optional)
 * @param integer $rank (optional) The rank of the TV
 * @param string $display (optional) The type of output render
 * @param string $display_params (optional) Any display rendering parameters
 * @param string $default_text (optional) The default value for the TV
 * @param json $templates (optional) Templates associated with the TV
 * @param json $resource_groups (optional) Resource Groups associated with the
 * TV.
 * @param json $propdata (optional) A json array of properties
 *
 * @package modx
 * @subpackage processors.element.tv
 */
$modx->lexicon->load('tv','category');

if (!$modx->hasPermission('save_template')) return $modx->error->failure($modx->lexicon('permission_denied'));

$tv = $modx->getObject('modTemplateVar',$_POST['id']);
if ($tv == null) return $modx->error->failure($modx->lexicon('tv_err_not_found'));

if ($tv->get('locked') && $modx->hasPermission('edit_locked') == false) {
    return $modx->error->failure($modx->lexicon('tv_err_locked'));
}

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
        if ($category->save() === false) {
            return $modx->error->failure($modx->lexicon('category_err_save'));
        }
    }
}

/* invoke OnBeforeTVFormSave event */
$modx->invokeEvent('OnBeforeTVFormSave',array(
    'mode' => 'upd',
    'id' => $tv->get('id'),
));

/* check to make sure name doesn't already exist */
$name_exists = $modx->getObject('modTemplateVar',array(
    'id:!=' => $tv->get('id'),
    'name' => $_POST['name']
));
if ($name_exists != null) $modx->error->addField('name',$modx->lexicon('tv_err_exists_name'));

if (!isset($_POST['name']) || $_POST['name'] == '') $_POST['name'] = $modx->lexicon('untitled_tv');
if ($_POST['caption'] == '') $_POST['caption'] = $_POST['name'];

/* extract widget properties */
$display_params = '';
foreach ($_POST as $key => $value) {
    $res = strstr($key,'prop_');
    if ($res !== false) {
        $key = str_replace('prop_','',$key);
        $display_params .= '&'.$key.'='.$value;
    }
}

$tv->fromArray($_POST);
$tv->set('elements',$_POST['els']);
$tv->set('display_params',$display_params);
$tv->set('rank', isset($_POST['rank']) ? $_POST['rank'] : 0);
$tv->set('locked', isset($_POST['locked']));
$tv->set('category',$category->get('id'));
$properties = null;
if (isset($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $tv->setProperties($properties);

if ($tv->save() === false) {
    return $modx->error->failure($modx->lexicon('tv_err_save'));
}


/* change template access to tvs */
if (isset($_POST['templates'])) {
    $_TEMPLATES = $modx->fromJSON($_POST['templates']);
    foreach ($_TEMPLATES as $id => $template) {
        if ($template['access']) {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv->get('id'),
                'templateid' => $template['id'],
            ));
            if ($tvt == null) {
                $tvt = $modx->newObject('modTemplateVarTemplate');
            }
            $tvt->set('tmplvarid',$tv->get('id'));
            $tvt->set('templateid',$template['id']);
            $tvt->set('rank',$template['rank']);
            $tvt->save();
        } else {
            $tvt = $modx->getObject('modTemplateVarTemplate',array(
                'tmplvarid' => $tv->get('id'),
                'templateid' => $template['id'],
            ));
            if ($tvt == null) continue;
            $tvt->remove();
        }
    }
}

/*
 * :TODO: Replace with appropriate ABAC approach check for permission update
 * access
 */
if ($modx->hasPermission('tv_access_permissions')) {
    if (isset($_POST['resource_groups'])) {
        $docgroups = $modx->fromJSON($_POST['resource_groups']);
        foreach ($docgroups as $id => $group) {
            $tvdg = $modx->getObject('modTemplateVarResourceGroup',array(
                'tmplvarid' => $tv->get('id'),
                'documentgroup' => $group['id'],
            ));

            if ($group['access'] == true) {
                if ($tvdg != null) continue;
                $tvdg = $modx->newObject('modTemplateVarResourceGroup');
                $tvdg->set('tmplvarid',$tv->get('id'));
                $tvdg->set('documentgroup',$group['id']);
                $tvdg->save();
            } else {
                $tvdg->remove();
            }
        }
    }
}

/* invoke OnTVFormSave event */
$modx->invokeEvent('OnTVFormSave',array(
    'mode' => 'upd',
    'id' => $tv->get('id'),
));

/* log manager action */
$modx->logManagerAction('tv_update','modTemplateVar',$tv->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();
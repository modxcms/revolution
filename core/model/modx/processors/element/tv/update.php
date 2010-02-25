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
if (!$modx->hasPermission('save_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv','category');

/* get tv */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('tv_err_ns'));
$tv = $modx->getObject('modTemplateVar',$_POST['id']);
if ($tv == null) return $modx->error->failure($modx->lexicon('tv_err_nf'));

/* check locks */
if ($tv->get('locked') && $modx->hasPermission('edit_locked') == false) {
    return $modx->error->failure($modx->lexicon('tv_err_locked'));
}

/* category */
if (!empty($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

if (empty($_POST['name'])) $_POST['name'] = $modx->lexicon('untitled_tv');

/* invoke OnBeforeTVFormSave event */
$modx->invokeEvent('OnBeforeTVFormSave',array(
    'mode' => 'upd',
    'id' => $tv->get('id'),
    'tv' => &$tv,
));

/* check to make sure name doesn't already exist */
$nameExists = $modx->getObject('modTemplateVar',array(
    'id:!=' => $tv->get('id'),
    'name' => $_POST['name'],
));
if ($nameExists != null) $modx->error->addField('name',$modx->lexicon('tv_err_exists_name'));


/* get rid of invalid chars */
$invchars = array('!','@','#','$','%','^','&','*','(',')','+','=',
    '[',']','{','}','\'','"',':',';','\\','/','<','>','?',' ',',','`','~');
$_POST['name'] = str_replace($invchars,'',$_POST['name']);


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
$tv->set('rank', !empty($_POST['rank']) ? $_POST['rank'] : 0);
$tv->set('locked', !empty($_POST['locked']));

if ($tv->save() === false) {
    return $modx->error->failure($modx->lexicon('tv_err_save'));
}


/* change template access to tvs */
if (isset($_POST['templates'])) {
    $templateVariables = $modx->fromJSON($_POST['templates']);
    if (is_array($templateVariables)) {
        foreach ($templateVariables as $id => $template) {
            if (!is_array($template)) continue;

            if ($template['access']) {
                $templateVarTemplate = $modx->getObject('modTemplateVarTemplate',array(
                    'tmplvarid' => $tv->get('id'),
                    'templateid' => $template['id'],
                ));
                if (empty($templateVarTemplate)) {
                    $templateVarTemplate = $modx->newObject('modTemplateVarTemplate');
                }
                $templateVarTemplate->set('tmplvarid',$tv->get('id'));
                $templateVarTemplate->set('templateid',$template['id']);
                $templateVarTemplate->set('rank',$template['rank']);
                $templateVarTemplate->save();
            } else {
                $templateVarTemplate = $modx->getObject('modTemplateVarTemplate',array(
                    'tmplvarid' => $tv->get('id'),
                    'templateid' => $template['id'],
                ));
                if ($templateVarTemplate && $templateVarTemplate instanceof modTemplateVarTemplate) {
                    $templateVarTemplate->remove();
                }
            }
        }
    }
}

/*
 * update access
 */
if ($modx->hasPermission('tv_access_permissions')) {
    if (isset($_POST['resource_groups'])) {
        $docgroups = $modx->fromJSON($_POST['resource_groups']);
        if (is_array($docgroups)) {
            foreach ($docgroups as $id => $group) {
                if (!is_array($group)) continue;

                $templateVarResourceGroup = $modx->getObject('modTemplateVarResourceGroup',array(
                    'tmplvarid' => $tv->get('id'),
                    'documentgroup' => $group['id'],
                ));

                if ($group['access'] == true) {
                    if (!empty($templateVarResourceGroup)) continue;
                    $templateVarResourceGroup = $modx->newObject('modTemplateVarResourceGroup');
                    $templateVarResourceGroup->set('tmplvarid',$tv->get('id'));
                    $templateVarResourceGroup->set('documentgroup',$group['id']);
                    $templateVarResourceGroup->save();
                } else {
                    $templateVarResourceGroup->remove();
                }
            }
        }
    }
}

/* invoke OnTVFormSave event */
$modx->invokeEvent('OnTVFormSave',array(
    'mode' => 'upd',
    'id' => $tv->get('id'),
    'tv' => &$tv,
));

/* log manager action */
$modx->logManagerAction('tv_update','modTemplateVar',$tv->get('id'));

/* empty cache */
if (!empty($_POST['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success();
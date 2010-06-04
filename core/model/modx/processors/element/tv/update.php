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
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('tv_err_ns'));
$tv = $modx->getObject('modTemplateVar',$scriptProperties['id']);
if ($tv == null) return $modx->error->failure($modx->lexicon('tv_err_nf'));

if (!$tv->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* check locks */
if ($tv->get('locked') && $modx->hasPermission('edit_locked') == false) {
    return $modx->error->failure($modx->lexicon('tv_err_locked'));
}

/* category */
if (!empty($scriptProperties['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $scriptProperties['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

if (empty($scriptProperties['name'])) $scriptProperties['name'] = $modx->lexicon('untitled_tv');

/* invoke OnBeforeTVFormSave event */
$modx->invokeEvent('OnBeforeTVFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $tv->get('id'),
    'tv' => &$tv,
));

/* check to make sure name doesn't already exist */
$nameExists = $modx->getObject('modTemplateVar',array(
    'id:!=' => $tv->get('id'),
    'name' => $scriptProperties['name'],
));
if ($nameExists != null) $modx->error->addField('name',$modx->lexicon('tv_err_exists_name'));


/* get rid of invalid chars */
//$invchars = array('!','@','#','$','%','^','&','*','(',')','+','=',
//    '[',']','{','}','\'','"',':',';','\\','/','<','>','?',' ',',','`','~');
//$scriptProperties['name'] = str_replace($invchars,'',$scriptProperties['name']);


/* extract widget properties */
$display_params = '';
foreach ($scriptProperties as $key => $value) {
    $res = strstr($key,'prop_');
    if ($res !== false) {
        $key = str_replace('prop_','',$key);
        $display_params .= '&'.$key.'='.$value;
    }
}

$tv->fromArray($scriptProperties);
$tv->set('elements',$scriptProperties['els']);
$tv->set('display_params',$display_params);
$tv->set('rank', !empty($scriptProperties['rank']) ? $scriptProperties['rank'] : 0);
$tv->set('locked', !empty($scriptProperties['locked']));

if (!$tv->validate()) {
    $validator = $tv->getValidator();
    if ($validator->hasMessages()) {
        foreach ($validator->getMessages() as $message) {
            $modx->error->addField($message['field'], $modx->lexicon($message['message']));
        }
    }
    if ($modx->error->hasError()) {
        return $modx->error->failure();
    }
}

if ($tv->save() === false) {
    return $modx->error->failure($modx->lexicon('tv_err_save'));
}


/* change template access to tvs */
if (isset($scriptProperties['templates'])) {
    $templateVariables = $modx->fromJSON($scriptProperties['templates']);
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
if ($modx->hasPermission('access_permissions')) {
    if (isset($scriptProperties['resource_groups'])) {
        $docgroups = $modx->fromJSON($scriptProperties['resource_groups']);
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
                } else if ($templateVarResourceGroup && $templateVarResourceGroup instanceof modTemplateVarResourceGroup) {
                    $templateVarResourceGroup->remove();
                }
            }
        }
    }
}

/* invoke OnTVFormSave event */
$modx->invokeEvent('OnTVFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $tv->get('id'),
    'tv' => &$tv,
));

/* log manager action */
$modx->logManagerAction('tv_update','modTemplateVar',$tv->get('id'));

/* empty cache */
if (!empty($scriptProperties['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success();
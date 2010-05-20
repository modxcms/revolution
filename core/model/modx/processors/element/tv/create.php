<?php
/**
 * Create a Template Variable.
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
if (!$modx->hasPermission('new_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv','category');

if (empty($scriptProperties['template'])) $scriptProperties['template'] = array();

/* category */
if (!empty($scriptProperties['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $scriptProperties['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
    if (!$category->checkPolicy('add_children')) return $modx->error->failure($modx->lexicon('access_denied'));
}

/* invoke OnBeforeTVFormSave event */
$modx->invokeEvent('OnBeforeTVFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => 0,
));

$name_exists = $modx->getObject('modTemplateVar',array('name' => $scriptProperties['name']));
if ($name_exists != null) $modx->error->addField('name',$modx->lexicon('tv_err_exists_name'));

if (empty($scriptProperties['name'])) $scriptProperties['name'] = $modx->lexicon('untitled_tv');

/* get rid of invalid chars */
$invchars = array('!','@','#','$','%','^','&','*','(',')','+','=',
    '[',']','{','}','\'','"',':',';','\\','/','<','>','?',' ',',','`','~');
$scriptProperties['name'] = str_replace($invchars,'',$scriptProperties['name']);

if (empty($scriptProperties['caption'])) { $scriptProperties['caption'] = $scriptProperties['name']; }

if ($modx->error->hasError()) return $modx->error->failure();

/* extract widget properties */
$display_params = '';
foreach ($scriptProperties as $key => $value) {
    $res = strstr($key,'prop_');
    if ($res !== false) {
        $key = str_replace('prop_','',$key);
        $display_params .= '&'.$key.'='.$value;
    }
}

$tv = $modx->newObject('modTemplateVar');
$tv->fromArray($scriptProperties);
$tv->set('elements',$scriptProperties['els']);
$tv->set('display_params',$display_params);
$tv->set('rank',!empty($scriptProperties['rank']) ? $scriptProperties['rank'] : 0);
$tv->set('locked',!empty($scriptProperties['locked']));
$properties = null;
if (isset($scriptProperties['propdata'])) {
    $properties = $scriptProperties['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $tv->setProperties($properties);

if ($tv->save() == false) {
    return $modx->error->failure($modx->lexicon('tv_err_save'));
}


/* change template access to tvs */
if (isset($scriptProperties['templates'])) {
    $_TEMPLATES = $modx->fromJSON($scriptProperties['templates']);
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
 * check for permission update access
 */
if ($modx->hasPermission('tv_access_permissions')) {
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
                    if ($templateVarResourceGroup->save() == false) {
                        return $modx->error->failure($modx->lexicon('tvdg_err_save'));
                    }
                } else {
                    if ($templateVarResourceGroup->remove() == false) {
                        return $modx->error->failure($modx->lexicon('tvdg_err_remove'));
                    }
                }
            }
        }
    }
}

/* invoke OnTVFormSave event */
$modx->invokeEvent('OnTVFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => $tv->get('id'),
    'tv' => &$tv,
));

/* log manager action */
$modx->logManagerAction('tv_create','modTemplateVar',$tv->get('id'));

/* empty cache */
if (!empty($scriptProperties['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}


return $modx->error->success('',$tv->get(array('id')));
<?php
/**
 * Creates a resource.
 *
 * @param string $pagetitle The page title.
 * @param string $content The HTML content. Used in conjunction with $ta.
 * @param integer $template (optional) The modTemplate to use with this
 * resource. Defaults to 0, or a blank template.
 * @param integer $parent (optional) The parent resource ID. Defaults to 0.
 * @param string $class_key (optional) The class key. Defaults to modDocument.
 * @param integer $menuindex (optional) The menu order. Defaults to 0.
 * @param string $variablesmodified (optional) A collection of modified TVs.
 * Along with $tv1, $tv2, etc.
 * @param string $context_key (optional) The context in which this resource is
 * located. Defaults to web.
 * @param string $alias (optional) The alias for FURLs that this resource is
 * designated to.
 * @param integer $content_type (optional) The content type. Defaults to
 * text/html.
 * @param boolean $published (optional) The published status.
 * @param string $pub_date (optional) The date on which this resource should
 * become published.
 * @param string $unpub_date (optional) The date on which this resource should
 * become unpublished.
 * @param string $publishedon (optional) The date this resource was published.
 * Defaults to time()
 * @param integer $publishedby (optional) The modUser who published this
 * resource. Defaults to the current user.
 * @param json $resource_groups (optional) A JSON array of resource groups to
 * assign this resource to.
 * @param boolean $hidemenu (optional) If true, The resource will not show up in
 * menu builders.
 * @param boolean $isfolder (optional) Whether or not the resource is a
 * container of resources.
 * @param boolean $richtext (optional) If true, MODx will render the available
 * RTE for editing this resource.
 * @param boolean $donthit (optional) (deprecated) If true, MODx will not log
 * visits on this resource.
 * @param boolean $cacheable (optional) If false, the resource will not be
 * cached.
 * @param boolean $searchable (optional) If false, the resource will not appear
 * in searches.
 * @param boolean $syncsite (optional) If false, will not empty the cache on
 * save.
 * @return array
 *
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('new_document')) return $modx->error->failure($modx->lexicon('resource_create_access_denied'));
$modx->lexicon->load('resource');

/* handle if parent is a context */
if (!empty($scriptProperties['parent']) && !is_numeric($scriptProperties['parent'])) {
    $ctx = $modx->getObject('modContext',array('key' => $scriptProperties['parent']));
    if ($ctx) {
        $scriptProperties['context_key'] = $scriptProperties['parent'];
    }
    $scriptProperties['parent'] = 0;
}

/* default settings */
$scriptProperties['context_key']= empty($scriptProperties['context_key']) ? 'web' : $scriptProperties['context_key'];
$scriptProperties['parent'] = empty($scriptProperties['parent']) ? 0 : intval($scriptProperties['parent']);
$scriptProperties['template'] = empty($scriptProperties['template']) ? 0 : intval($scriptProperties['template']);
$scriptProperties['hidemenu'] = empty($scriptProperties['hidemenu']) ? 0 : 1;
$scriptProperties['isfolder'] = empty($scriptProperties['isfolder']) ? 0 : 1;
$scriptProperties['richtext'] = empty($scriptProperties['richtext']) ? 0 : 1;
$scriptProperties['donthit'] = empty($scriptProperties['donthit']) ? 0 : 1;
$scriptProperties['published'] = empty($scriptProperties['published']) ? 0 : 1;
$scriptProperties['cacheable'] = empty($scriptProperties['cacheable']) ? 0 : 1;
$scriptProperties['searchable'] = empty($scriptProperties['searchable']) ? 0 : 1;
$scriptProperties['syncsite'] = empty($scriptProperties['syncsite']) ? 0 : 1;
$scriptProperties['createdon'] = strftime('%Y-%m-%d %H:%M:%S');
$scriptProperties['menuindex'] = empty($scriptProperties['menuindex']) ? 0 : $scriptProperties['menuindex'];
$scriptProperties['deleted'] = empty($scriptProperties['deleted']) ? 0 : 1;

/* make sure parent exists and user can add_children to the parent */
$parent = null;
if ($scriptProperties['parent'] > 0) {
    $parent = $modx->getObject('modResource', $scriptProperties['parent']);
    if ($parent) {
        if (!$parent->checkPolicy('add_children')) {
            return $modx->error->failure($modx->lexicon('resource_add_children_access_denied'));
        }
    } else {
        return $modx->error->failure($modx->lexicon('resource_err_nfs', array('id' => $scriptProperties['parent'])));
    }
} elseif (!$modx->hasPermission('new_document_in_root')) {
    return $modx->error->failure($modx->lexicon('resource_add_children_access_denied'));
}

/* default pagetitle */
if (empty($scriptProperties['pagetitle'])) $scriptProperties['pagetitle'] = $modx->lexicon('resource_untitled');

/* specific data escaping */
$scriptProperties['pagetitle'] = trim($scriptProperties['pagetitle']);
$scriptProperties['variablesmodified'] = isset($scriptProperties['variablesmodified'])
    ? explode(',',$scriptProperties['variablesmodified'])
    : array();

/* get the class_key to determine resourceClass and resourceDir */
$resourceClass = !empty($scriptProperties['class_key']) ? $scriptProperties['class_key'] : 'modDocument';
$resourceDir= strtolower(substr($resourceClass, 3));

/* create the new resource instance using the indicated class */
$resource = $modx->newObject($resourceClass);
if (!$resource) return $modx->error->failure($modx->lexicon('resource_err_create'));
if (!$resource instanceof $resourceClass) return $modx->error->failure($modx->lexicon('resource_err_class',array('class' => $resourceClass)));

/* friendly url alias checks */
if ($modx->getOption('friendly_alias_urls')) {
    /* auto assign alias */
    if (empty($scriptProperties['alias']) && $modx->getOption('automatic_alias')) {
        $scriptProperties['alias'] = $resource->cleanAlias($scriptProperties['pagetitle']);
    } else {
        $scriptProperties['alias'] = $resource->cleanAlias($scriptProperties['alias']);
    }
    $resourceContext= $modx->getObject('modContext', $scriptProperties['context_key']);
    $resourceContext->prepare();

    $fullAlias= $scriptProperties['alias'];
    $isHtml= true;
    $extension= '';
    $containerSuffix= $modx->getOption('container_suffix',null,'');
    if (isset ($scriptProperties['content_type']) && $contentType= $modx->getObject('modContentType', $scriptProperties['content_type'])) {
        $extension= $contentType->getExtension();
        $isHtml= (strpos($contentType->get('mime_type'), 'html') !== false);
    }
    if ($scriptProperties['isfolder'] && $isHtml && !empty ($containerSuffix)) {
        $extension= $containerSuffix;
    }
    $aliasPath= '';
    if ($modx->getOption('use_alias_path')) {
        $pathParentId= intval($scriptProperties['parent']);
        $parentResources= array ();
        $currResource= $modx->getObject('modResource', $pathParentId);
        while ($currResource) {
            $parentAlias= $currResource->get('alias');
            if (empty ($parentAlias))
                $parentAlias= "{$pathParentId}";
            $parentResources[]= "{$parentAlias}";
            $pathParentId= $currResource->get('parent');
            $currResource= $currResource->getOne('Parent');
        }
        $aliasPath= !empty ($parentResources) ? implode('/', array_reverse($parentResources)) : '';
    }
    $fullAlias= $aliasPath . $fullAlias . $extension;

    if (isset ($resourceContext->aliasMap[$fullAlias])) {
        $duplicateId= $resourceContext->aliasMap[$fullAlias];
        $err = $modx->lexicon('duplicate_alias_found',array(
            'id' => $duplicateId,
            'alias' => $fullAlias,
        ));
        $modx->error->addField('alias', $err);
    }
}
if ($modx->error->hasError()) return $modx->error->failure();

/* publish and unpublish dates */
$now = time();
if (isset($scriptProperties['pub_date'])) {
    if (empty($scriptProperties['pub_date'])) {
        $scriptProperties['pub_date'] = 0;
    } else {
        $scriptProperties['pub_date'] = strtotime($scriptProperties['pub_date']);
        if ($scriptProperties['pub_date'] < $now) $scriptProperties['published'] = 1;
        if ($scriptProperties['pub_date'] > $now) $scriptProperties['published'] = 0;
    }
}

if (isset($scriptProperties['unpub_date'])) {
    if (empty($scriptProperties['unpub_date'])) {
        $scriptProperties['unpub_date'] = 0;
    } else {
        $scriptProperties['unpub_date'] = strtotime($scriptProperties['unpub_date']);
        if ($scriptProperties['unpub_date'] < $now) $scriptProperties['published'] = 0;
    }
}

if (!empty($scriptProperties['template']) && ($template = $modx->getObject('modTemplate', $scriptProperties['template']))) {
    $tmplvars = array();
    $c = $modx->newQuery('modTemplateVar');
    $c->select('DISTINCT `modTemplateVar`.*, `modTemplateVar`.`default_text` AS `value`');
    $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplates');
    $c->where(array(
        'TemplateVarTemplates.templateid' => $scriptProperties['template'],
    ));
    $c->sortby('modTemplateVar.rank');
    $templateVars = $modx->getCollection('modTemplateVar',$c);

    foreach ($templateVars as $tv) {
        $value = isset($scriptProperties['tv'.$tv->get('id')]) ? $scriptProperties['tv'.$tv->get('id')] : $tv->get('default_text');

        switch ($tv->get('type')) {
            case 'url':
                if ($scriptProperties['tv' . $row['name'] . '_prefix'] != '--') {
                    $value = str_replace(array('ftp://','http://'),'', $value);
                    $value = $scriptProperties['tv'.$tv->get('id').'_prefix'].$value;
                }
                break;
            default:
                /* handles checkboxes & multiple selects elements */
                if (is_array($value)) {
                    $featureInsert = array();
                    while (list($featureValue, $featureItem) = each($value)) {
                        $featureInsert[count($featureInsert)] = $featureItem;
                    }
                    $value = implode('||',$featureInsert);
                }
                break;
        }

        /* save value if it was modified */
        $default = $tv->processBindings($tv->get('default_text'),0);
        if (strcmp($value,$default) != 0) {
            $templateVarResource = $modx->newObject('modTemplateVarResource');
            $templateVarResource->set('tmplvarid',$tv->get('id'));
            $templateVarResource->set('value',$value);
            $tmplvars[] = $templateVarResource;
        }
    }
    $resource->addMany($tmplvars);
}

/* deny publishing if not permitted */
if (!$modx->hasPermission('publish_document')) {
    $scriptProperties['publishedon'] = 0;
    $scriptProperties['publishedby'] = 0;
    $scriptProperties['pub_date'] = 0;
    $scriptProperties['unpub_date'] = 0;
    $scriptProperties['published'] = 0;
}

if (isset($scriptProperties['published'])) {
    if (empty($scriptProperties['publishedon'])) {
        $scriptProperties['publishedon'] = $scriptProperties['published'] ? time() : 0;
    } else {
        $scriptProperties['publishedon'] = strtotime($scriptProperties['publishedon']);
    }
    $scriptProperties['publishedby'] = $scriptProperties['published'] ? $modx->user->get('id') : 0;
}

/* load delegate processor */
$delegateProcessor= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateProcessor)) {
    $overridden= include ($delegateProcessor);
    return $overridden;
}

/* modDocument content is posted as ta */
if (isset($scriptProperties['ta'])) $scriptProperties['content'] = $scriptProperties['ta'];

/* set fields */
$resource->fromArray($scriptProperties);
if (!$resource->get('class_key')) {
    $resource->set('class_key', $resourceClass);
}

/* increase menu index if this is a new resource */
$auto_menuindex = $modx->getOption('auto_menuindex',null,true);
if (!empty($auto_menuindex)) {
    $menuindex = $modx->getCount('modResource',array('parent' => $resource->get('parent')));
}
$resource->set('menuindex',!empty($menuindex) ? $menuindex : 0);

/* invoke OnBeforeDocFormSave event */
$modx->invokeEvent('OnBeforeDocFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => 0,
    'resource' => &$resource,
));

/* save data */
if ($resource->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_err_save'));
}

/* add lock */
$resource->addLock();

/* update parent to be a container if user has save permission */
if ($parent && $parent instanceof modResource && $parent->checkPolicy('save')) {
    $parent->set('isfolder', true);
    $parent->save();
}

/* save resource groups */
if (isset($scriptProperties['resource_groups'])) {
    $resourceGroups = $modx->fromJSON($scriptProperties['resource_groups']);
    if (is_array($resourceGroups)) {
        foreach ($resourceGroups as $id => $resourceGroupAccess) {
            if (!empty($resourceGroupAccess['access'])) {
                $resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
                    'document_group' => $resourceGroupAccess['id'],
                    'document' => $resource->get('id'),
                ));
                if (empty($resourceGroupResource)) {
                    $resourceGroupResource = $modx->newObject('modResourceGroupResource');
                }
                $resourceGroupResource->set('document_group',$resourceGroupAccess['id']);
                $resourceGroupResource->set('document',$resource->get('id'));
                $resourceGroupResource->save();
            } else {
                $resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
                    'document_group' => $resourceGroupAccess['id'],
                    'document' => $resource->get('id'),
                ));
                if ($resourceGroupResource instanceof modResourceGroupResource) {
                    $resourceGroupResource->remove();
                }
            }
        }
    }
}

/* quick check to make sure it's not site_start, if so, publish */
if ($resource->get('id') == $modx->getOption('site_start')) {
    $resource->set('published',true);
    $resource->save();
}

/* invoke OnDocFormSave event */
$modx->invokeEvent('OnDocFormSave', array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => $resource->get('id'),
    'resource' => &$resource,
));

/* log manager action */
$modx->logManagerAction('save_resource', $resourceClass, $resource->get('id'));

if (!empty($scriptProperties['syncsite']) || !empty($scriptProperties['clearCache'])) {
    /* empty cache */
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache(array (
            "{$resource->context_key}/",
        ),
        array(
            'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
            'publishing' => true
        )
    );
}

/* remove lock */
$resource->removeLock();

return $modx->error->success('', array('id' => $resource->get('id')));
<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

/* specific data escaping */
$scriptProperties['pagetitle'] = trim($scriptProperties['pagetitle']);
$scriptProperties['variablesmodified'] = isset($scriptProperties['variablesmodified'])
    ? explode(',',$scriptProperties['variablesmodified'])
    : array();

/* default pagetitle */
if (empty($scriptProperties['pagetitle'])) $scriptProperties['pagetitle'] = $modx->lexicon('untitled_resource');

$scriptProperties['hidemenu'] = empty($scriptProperties['hidemenu']) ? 0 : 1;
$scriptProperties['isfolder'] = empty($scriptProperties['isfolder']) ? 0 : 1;
$scriptProperties['richtext'] = empty($scriptProperties['richtext']) ? 0 : 1;
$scriptProperties['donthit'] = empty($scriptProperties['donthit']) ? 0 : 1;
$scriptProperties['published'] = empty($scriptProperties['published']) ? 0 : 1;
$scriptProperties['cacheable'] = empty($scriptProperties['cacheable']) ? 0 : 1;
$scriptProperties['searchable'] = empty($scriptProperties['searchable']) ? 0 : 1;
$scriptProperties['syncsite'] = empty($scriptProperties['syncsite']) ? 0 : 1;


/* friendly url alias checks */
if ($modx->getOption('friendly_alias_urls')) {
    /* auto assign alias */
    if ($scriptProperties['alias'] == '' && $modx->getOption('automatic_alias')) {
        $scriptProperties['alias'] = $resource->cleanAlias(strtolower(trim($scriptProperties['pagetitle'])));
    } else {
        $scriptProperties['alias'] = $resource->cleanAlias($scriptProperties['alias']);
    }

    $resourceContext= $resource->getOne('Context');
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
        if ($duplicateId != $resource->get('id')) {
            $err = $modx->lexicon('duplicate_alias_found',array(
                'id' => $duplicateId,
                'alias' => $fullAlias,
            ));
            $modx->error->addField('alias', $err);
        }
    }
}

if ($modx->error->hasError()) return $modx->error->failure();


/* publish and unpublish dates */
$now = time();
if (empty($scriptProperties['pub_date'])) {
    $scriptProperties['pub_date'] = 0;
} else {
    $scriptProperties['pub_date'] = strtotime($scriptProperties['pub_date']);
    if ($scriptProperties['pub_date'] < $now) $scriptProperties['published'] = 1;
    if ($scriptProperties['pub_date'] > $now) $scriptProperties['published'] = 0;
}

if (empty($scriptProperties['unpub_date'])) {
    $scriptProperties['unpub_date'] = 0;
} else {
    $scriptProperties['unpub_date'] = strtotime($scriptProperties['unpub_date']);
    if ($scriptProperties['unpub_date'] < $now) {
        $scriptProperties['published'] = 0;
    }
}

/* Deny publishing if not permitted */
if (!$modx->hasPermission('publish_document')) {
    $scriptProperties['pub_date'] = 0;
    $scriptProperties['unpub_date'] = 0;
    $scriptProperties['published'] = 0;
}

if (!isset($scriptProperties['publishedon']) || $scriptProperties['publishedon'] == '') {
    $scriptProperties['publishedon'] = $scriptProperties['published'] ? time() : 0;
} else {
    $scriptProperties['publishedon'] = strtotime($scriptProperties['publishedon']);
}
$scriptProperties['publishedby'] = $scriptProperties['published'] ? $modx->user->get('id') : 0;

/* get parent */
$oldparent = $modx->getObject('modResource',$resource->get('parent'));

if ($resource->get('id') == $modx->getOption('site_start') && $scriptProperties['published'] == 0) {
    return $modx->error->failure($modx->lexicon('document_err_unpublish_sitestart'));
}
if ($resource->get('id') == $modx->getOption('site_start') && ($scriptProperties['pub_date'] != '0' || $scriptProperties['unpub_date'] != '0')) {
    return $modx->error->failure($modx->lexicon('document_err_unpublish_sitestart_dates'));
}

$count_children = $modx->getCount('modResource',array('parent' => $resource->get('id')));
$scriptProperties['isfolder'] = $count_children > 0;

/* Keep original publish state, if change is not permitted */
if (!$modx->hasPermission('publish_document')) {
    $scriptProperties['publishedon'] = $resource->get('publishedon');
    $scriptProperties['pub_date'] = $resource->get('pub_date');
    $scriptProperties['unpub_date'] = $resource->get('unpub_date');
}

/* invoke OnBeforeDocFormSave event */
$modx->invokeEvent('OnBeforeDocFormSave',array(
    'mode' => 'upd',
    'id' => $resource->get('id'),
));

/* Now save data */
unset($scriptProperties['variablesmodified']);
$resource->fromArray($scriptProperties);
$resource->set('editedby', $modx->user->get('id'));
$resource->set('editedon', time(), 'integer');

if ($resource->save() == false) {
    return $modx->error->failure($modx->lexicon('document_err_save'));
}

/* Save resource groups */
if (isset($scriptProperties['resource_groups'])) {
    $_GROUPS = $modx->fromJSON($scriptProperties['resource_groups']);
    foreach ($_GROUPS as $id => $group) {
        if ($group['access']) {
            $rgr = $modx->getObject('modResourceGroupResource',array(
                'document_group' => $group['id'],
                'document' => $resource->get('id'),
            ));
            if ($rgr == null) {
                $rgr = $modx->newObject('modResourceGroupResource');
            }
            $rgr->set('document_group',$group['id']);
            $rgr->set('document',$resource->get('id'));
            $rgr->save();
        } else {
            $rgr = $modx->getObject('modResourceGroupResource',array(
                'document_group' => $group['id'],
                'document' => $resource->get('id'),
            ));
            if ($rgr == null) continue;
            $rgr->remove();
        }
    }
}

/* TVs save */
if (!empty($scriptProperties['tvs'])) {
    $c = $modx->newQuery('modTemplateVar');
    $c->setClassAlias('tv');
    $c->innerJoin('modTemplateVarTemplate', 'tvtpl', array(
        'tvtpl.tmplvarid = tv.id',
        'tvtpl.templateid' => $scriptProperties['template']
    ));
    $c->leftJoin('modTemplateVarResource', 'tvc', array(
        'tvc.tmplvarid = tv.id',
        'tvc.contentid' => $resource->get('id'),
    ));
    $c->select(array(
        'DISTINCT tv.*',
        "IF(tvc.value != '',tvc.value,tv.default_text) AS value"
    ));
    $c->sortby('tv.rank');

    $tvs = $modx->getCollection('modTemplateVar',$c);
    foreach ($tvs as $tv) {
        /* set value of TV */
        $value = isset($scriptProperties['tv'.$tv->get('id')]) ? $scriptProperties['tv'.$tv->get('id')] : $tv->get('default_text');

        /* validation for different types */
        switch ($tv->get('type')) {
            case 'url':
                if ($scriptProperties['tv'.$tv->get('id').'_prefix'] != '--') {
                    $value = str_replace(array('ftp://','http://'),'', $value);
                    $value = $scriptProperties['tv'.$tv->get('id').'_prefix'].$value;
                }
                break;
            case 'date':
                $value = empty($value) ? '' : strftime('%Y-%m-%d %H:%M:%S',strtotime($value));
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

        /* if different than default and set, set TVR record */
        if ($value != $tv->get('default_text')) {

            /* update the existing record */
            $tvc = $modx->getObject('modTemplateVarResource',array(
                'tmplvarid' => $tv->get('id'),
                'contentid' => $resource->get('id'),
            ));
            if ($tvc == null) {
                /* add a new record */
                $tvc = $modx->newObject('modTemplateVarResource');
                $tvc->set('tmplvarid',$tv->get('id'));
                $tvc->set('contentid',$resource->get('id'));
            }
            $tvc->set('value',$value);
            $tvc->save();

        /* if equal to default value, erase TVR record */
        } else {
            $tvc = $modx->getObject('modTemplateVarResource',array(
                'tmplvarid' => $tv->get('id'),
                'contentid' => $resource->get('id'),
            ));
            if ($tvc != null) $tvc->remove();
        }
    }
}
/* end save TVs */

/* invoke OnDocFormSave event */
$modx->invokeEvent('OnDocFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $resource->get('id'),
    'resource' => & $resource
));

/* log manager action */
$modx->logManagerAction('save_resource','modResource',$resource->get('id'));

if ($scriptProperties['syncsite'] == 1) {
    /* empty cache */
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache(array (
            "{$resource->context_key}/resources/",
            "{$resource->context_key}/context.cache.php",
        ),
        array(
            'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
            'publishing' => true
        )
    );
}

$resource->removeLock();

$resourceArray = $resource->get(array('id','alias'));
$resourceArray['preview_url'] = $modx->makeUrl($resource->get('id'));

return $modx->error->success('',$resourceArray);
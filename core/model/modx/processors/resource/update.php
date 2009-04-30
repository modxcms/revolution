<?php
/**
 * Updates a resource.
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
$modx->lexicon->load('resource');

if (!isset($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$_REQUEST['id']);
if ($resource == null) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));

if (!$modx->hasPermission('save_document') || !$resource->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$resourceClass = isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : $resource->get('class_key');
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateProcessor= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateProcessor)) {
    $overridden= include ($delegateProcessor);
    return $overridden;
    /* need another way to handle this, since now handled with return statements
    if ($overridden !== false) {
        return $modx->error->failure('Warning! Delegate processor did not provide appropriate response.');
    }*/
}

/* specific data escaping */
$_POST['pagetitle'] = trim($_POST['pagetitle']);
$_POST['variablesmodified'] = isset($_POST['variablesmodified'])
    ? explode(',',$_POST['variablesmodified'])
    : array();
if (isset($_POST['ta'])) $_POST['content'] = $_POST['ta'];

/* default pagetitle */
if ($_POST['pagetitle'] == '') $_POST['pagetitle'] = $modx->lexicon('resource_untitled');

$_POST['hidemenu'] = !isset($_POST['hidemenu']) ? 0 : 1;
$_POST['isfolder'] = !isset($_POST['isfolder']) ? 0 : 1;
$_POST['richtext'] = !isset($_POST['richtext']) ? 0 : 1;
$_POST['donthit'] = !isset($_POST['donthit']) ? 0 : 1;
$_POST['published'] = !isset($_POST['published']) ? 0 : 1;
$_POST['cacheable'] = !isset($_POST['cacheable']) ? 0 : 1;
$_POST['searchable'] = !isset($_POST['searchable']) ? 0 : 1;
$_POST['syncsite'] = !isset($_POST['syncsite']) ? 0 : 1;

/* friendly url alias checks */
if ($modx->config['friendly_alias_urls']) {
    /* auto assign alias */
    if ($_POST['alias'] == '' && $modx->config['automatic_alias']) {
        $_POST['alias'] = $resource->cleanAlias(strtolower(trim($_POST['pagetitle'])));
    } else {
        $_POST['alias'] = $resource->cleanAlias($_POST['alias']);
    }

    $resourceContext= $resource->getOne('Context');
    $resourceContext->prepare();

    $fullAlias= $_POST['alias'];
    $isHtml= true;
    $extension= '';
    $containerSuffix= isset ($modx->config['container_suffix']) ? $modx->config['container_suffix'] : '';
    if (isset ($_POST['content_type']) && $contentType= $modx->getObject('modContentType', $_POST['content_type'])) {
        $extension= $contentType->getExtension();
        $isHtml= (strpos($contentType->get('mime_type'), 'html') !== false);
    }
    if ($_POST['isfolder'] && $isHtml && !empty ($containerSuffix)) {
        $extension= $containerSuffix;
    }
    $aliasPath= '';
    if ($modx->config['use_alias_path']) {
        $pathParentId= intval($_POST['parent']);
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
            $err = sprintf($modx->lexicon('duplicate_alias_found'), $duplicateId, $fullAlias);
            $modx->error->addField('alias', $err);
        }
    }
}

if ($modx->error->hasError()) return $modx->error->failure();

/* publish and unpublish dates */
$now = time();
if (empty($_POST['pub_date'])) {
    $_POST['pub_date'] = 0;
} else {
    $_POST['pub_date'] = strtotime($_POST['pub_date']);
    if ($_POST['pub_date'] < $now) $_POST['published'] = 1;
    if ($_POST['pub_date'] > $now) $_POST['published'] = 0;
}

if (empty($_POST['unpub_date'])) {
    $_POST['unpub_date'] = 0;
} else {
    $_POST['unpub_date'] = strtotime($_POST['unpub_date']);
    if ($_POST['unpub_date'] < $now) {
        $_POST['published'] = 0;
    }
}

$tmplvars = array ();

$c = $modx->newQuery('modTemplateVar');
$c->_alias = 'tv';
$c->innerJoin('modTemplateVarTemplate', 'tvtpl', array(
    'tvtpl.tmplvarid = tv.id',
    'tvtpl.templateid' => $_POST['template']
));
$c->leftJoin('modTemplateVarResource', 'tvc', array(
    'tvc.tmplvarid = tv.id',
    'tvc.contentid' => $resource->get('id')
));
$c->select(array(
    'DISTINCT tv.*',
    "IF(tvc.value != '',tvc.value,tv.default_text) AS value"
));
$c->sortby('tv.rank');

$tvs = $modx->getCollection('modTemplateVar',$c);
foreach ($tvs as $tv) {
    $tmplvar = '';
    if ($tv->get('type') == 'url') {
        $tmplvar = $_POST['tv'.$tv->get('id')];
        if ($_POST['tv'.$tv->get('id').'_prefix'] != '--') {
            $tmplvar = str_replace(array('ftp://','http://'),'', $tmplvar);
            $tmplvar = $_POST['tv'.$tv->get('id').'_prefix'].$tmplvar;
        }
    } elseif ($tv->get('type') == 'file') {
        $tmplvar = $_POST['tv'.$tv->get('id')];
    } elseif ($tv->get('type') == 'date') {
        $tmplvar = $_POST['tv'.$tv->get('id')] != ''
            ? strftime('%Y-%m-%d %H:%M:%S',strtotime($_POST['tv'.$tv->get('id')]))
            : '';
    } else {
        if (is_array($_POST['tv'.$tv->get('id')])) {
            /* handles checkboxes & multiple selects elements */
            $feature_insert = array ();
            $lst = $_POST['tv'.$tv->get('id')];
            while (list($featureValue, $feature_item) = each($lst)) {
                $feature_insert[count($feature_insert)] = $feature_item;
            }
            $tmplvar = implode('||',$feature_insert);
        } else {
            $tmplvar = $_POST['tv'.$tv->get('id')];
        }
    }
    if (strlen($tmplvar) > 0 && $tmplvar != $tv->get('default_text')) {
        $tmplvars[$tv->get('id')] = array (
            $tv->get('id'),
            $tmplvar,
        );
    } else $tmplvars[$tv->get('id')] = $tv->get('id');
}

/* Deny publishing if not permitted */
if (!$modx->hasPermission('publish_document')) {
    $_POST['pub_date'] = 0;
    $_POST['unpub_date'] = 0;
    $_POST['published'] = 0;
}

if (!isset($_POST['publishedon']) || $_POST['publishedon'] == '') {
    $_POST['publishedon'] = $_POST['published'] ? time() : 0;
} else {
    $_POST['publishedon'] = strtotime($_POST['publishedon']);
}
$_POST['publishedby'] = $_POST['published'] ? $modx->user->get('id') : 0;

/* get parent */
$oldparent_id = $resource->get('parent');
if ($resource->get('id') == $modx->config['site_start'] && $_POST['published'] == 0) {
    return $modx->error->failure($modx->lexicon('resource_err_unpublish_sitestart'));
}
if ($resource->get('id') == $modx->config['site_start'] && ($_POST['pub_date'] != 0 || $_POST['unpub_date'] != 0)) {
    return $modx->error->failure($modx->lexicon('resource_err_unpublish_sitestart_dates'));
}

$count_children = $modx->getCount('modResource',array('parent' => $resource->get('id')));
$_POST['isfolder'] = $count_children > 0;

/* Keep original publish state, if change is not permitted */
if (!$modx->hasPermission('publish_document')) {
    $_POST['publishedon'] = $resource->get('publishedon');
    $_POST['pub_date'] = $resource->get('pub_date');
    $_POST['unpub_date'] = $resource->get('unpub_date');
}

/* invoke OnBeforeDocFormSave event */
$modx->invokeEvent('OnBeforeDocFormSave',array(
    'mode' => 'upd',
    'id' => $resource->get('id'),
));

/* Now save data */
unset($_POST['variablesmodified']);
$resource->fromArray($_POST);

$resource->set('editedby', $modx->user->get('id'));
$resource->set('editedon', time(), 'integer');


if ($resource->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_err_save'));
}

/* if parent changed, change folder status of old and new parents */
if ($resource->get('parent') != $oldparent_id) {
    $oldparent = $modx->getObject('modResource',$oldparent_id);
    if ($oldparent != null) {
        $opc = $modx->getCount('modResource',array('parent' => $oldparent->get('id')));
        if ($opc <= 0 || $opc == null) {
            $oldparent->set('isfolder',false);
            $oldparent->save();
        }
    }

    $newparent = $modx->getObject('modResource',$resource->get('parent'));
    if ($newparent != null) {
        $newparent->set('isfolder',true);
        $newparent->save();
    }
}

foreach ($tmplvars as $field => $value) {
     if (!is_array($value)) {
        /* delete unused variable */
        $tvc = $modx->getObject('modTemplateVarResource',array(
            'tmplvarid' => $value,
            'contentid' => $resource->get('id'),
        ));
        if ($tvc != null) $tvc->remove();
    } else {
        /* update the existing record */
        $tvc = $modx->getObject('modTemplateVarResource',array(
            'tmplvarid' => $value[0],
            'contentid' => $resource->get('id'),
        ));
        if ($tvc == null) {
            /* add a new record */
            $tvc = $modx->newObject('modTemplateVarResource');
            $tvc->set('tmplvarid',$value[0]);
            $tvc->set('contentid',$resource->get('id'));
        }
        $tvc->set('value',$value[1]);
        $tvc->save();
    }
}

/* Save resource groups */
if (isset($_POST['resource_groups'])) {
    $_GROUPS = $modx->fromJSON($_POST['resource_groups']);
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

/* Save META Keywords */
if ($modx->hasPermission('edit_doc_metatags')) {
    /* keywords - remove old keywords first */
    $okws = $modx->getCollection('modResourceKeyword',array('content_id' => $resource->get('id')));
    foreach ($okws as $kw) $kw->remove();

    if (is_array($_POST['keywords'])) {
        foreach ($_POST['keywords'] as $keyword) {
            $kw = $modx->newObject('modResourceKeyword');
            $kw->set('content_id',$resource->get('id'));
            $kw->set('keyword_id',$keyword);
            $kw->save();
        }
    }

    /* meta tags - remove old tags first */
    $omts = $modx->getCollection('modResourceMetatag',array('content_id' => $resource->get('id')));
    foreach ($omts as $mt) $mt->remove();

    if (is_array($_POST['metatags'])) {
        foreach ($_POST['metatags'] as $metatag) {
            $mt = $modx->newObject('modResourceMetatag');
            $mt->set('content_id',$resource->get('id'));
            $mt->set('metatag_id',$metatag);
            $mt->save();
        }
    }

    if ($resource != null) {
        $resource->set('haskeywords',count($keywords) ? 1 : 0);
        $resource->set('hasmetatags',count($metatags) ? 1 : 0);
        $resource->save();
    }
}

/* invoke OnDocFormSave event */
$modx->invokeEvent('OnDocFormSave',array(
    'mode' => 'upd',
    'id' => $resource->get('id'),
    'resource' => & $resource
));

/* log manager action */
$modx->logManagerAction('save_resource','modResource',$resource->get('id'));

if ($_POST['syncsite'] == 1) {
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

return $modx->error->success('', $resource->get(array('id')));
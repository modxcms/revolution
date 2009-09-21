<?php
/**
 * @package modx
 * @subpackage processors.resource.staticresource
 */
if ($resourceClass != 'modStaticResource') return $modx->error->failure('Resource class is incorrect.');

$resource = $modx->newObject($resourceClass);

/* specific data escaping */
$_POST['pagetitle'] = trim($_POST['pagetitle']);
$_POST['variablesmodified'] = isset($_POST['variablesmodified'])
    ? explode(',',$_POST['variablesmodified'])
    : array();

$_POST['parent'] = empty($_POST['parent']) ? 0 : intval($_POST['parent']);
$_POST['context_key']= empty($_POST['context_key']) ? 'web' : $_POST['context_key'];
$_POST['parent'] = empty($_POST['parent']) ? 0 : intval($_POST['parent']);
$_POST['isfolder'] = empty($_POST['isfolder']) ? 0 : 1;
$_POST['hidemenu'] = empty($_POST['hidemenu']) ? 0 : 1;
$_POST['richtext'] = empty($_POST['richtext']) ? 0 : 1;
$_POST['donthit'] = empty($_POST['donthit']) ? 0 : 1;
$_POST['published'] = empty($_POST['published']) ? 0 : 1;
$_POST['cacheable'] = empty($_POST['cacheable']) ? 0 : 1;
$_POST['searchable'] = empty($_POST['searchable']) ? 0 : 1;
$_POST['syncsite'] = empty($_POST['syncsite']) ? 0 : 1;
$_POST['menuindex'] = empty($_POST['menuindex']) ? 0 : $_POST['menuindex'];
$_POST['createdon'] = strftime('%Y-%m-%d %H:%M:%S');

/* default pagetitle */
if (empty($_POST['pagetitle'])) $_POST['pagetitle'] = $modx->lexicon('untitled_resource');

$_POST['context_key']= empty($_POST['context_key']) ? 'web' : $_POST['context_key'];

/* friendly url alias checks */
if ($modx->getOption('friendly_alias_urls')) {
    /* auto assign alias */
    if ($_POST['alias'] == '' && $modx->getOption('automatic_alias')) {
        $_POST['alias'] = strtolower(trim($resource->cleanAlias($_POST['pagetitle'])));
    } else {
        $_POST['alias'] = $resource->cleanAlias($_POST['alias']);
    }
    $resourceContext= $modx->getObject('modContext', $_POST['context_key']);
    $resourceContext->prepare();

    $fullAlias= $_POST['alias'];
    $isHtml= true;
    $extension= '';
    $containerSuffix= $modx->getOption('container_suffix',null,'');
    if (isset ($_POST['content_type']) && $contentType= $modx->getObject('modContentType', $_POST['content_type'])) {
        $extension= $contentType->getExtension();
        $isHtml= (strpos($contentType->get('mime_type'), 'html') !== false);
    }
    if ($_POST['isfolder'] && $isHtml && !empty ($containerSuffix)) {
        $extension= $containerSuffix;
    }
    $aliasPath= '';
    if ($modx->getOption('use_alias_path')) {
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
if (isset($_POST['pub_date'])) {
    if (empty($_POST['pub_date'])) {
        $_POST['pub_date'] = 0;
    } else {
        list ($d, $m, $Y, $H, $M, $S) = sscanf($_POST['pub_date'],"%2d-%2d-%4d %2d:%2d:%2d");
        $_POST['pub_date'] = mktime($H, $M, $S, $m, $d, $Y);
        if ($_POST['pub_date'] < $now) $_POST['published'] = 1;
        if ($_POST['pub_date'] > $now) $_POST['published'] = 0;
    }
}
if (isset($_POST['unpub_date'])) {
    if (empty($_POST['unpub_date'])) {
        $_POST['unpub_date'] = 0;
    } else {
        list ($d, $m, $Y, $H, $M, $S) = sscanf($_POST['unpub_date'], "%2d-%2d-%4d %2d:%2d:%2d");
        $_POST['unpub_date'] = mktime($H, $M, $S, $m, $d, $Y);
        if ($_POST['unpub_date'] < $now) {
            $_POST['published'] = 0;
        }
    }
}


if (!empty($_POST['template']) && ($template = $modx->getObject('modTemplate', $_POST['template']))) {
    $tmplvars = array();
    $c = $modx->newQuery('modTemplateVar');
    $c->select('DISTINCT modTemplateVar.*, modTemplateVar.default_text AS value');
    $c->innerJoin('modTemplateVarTemplate','modTemplateVarTemplate');
    $c->where(array(
        'modTemplateVarTemplate.templateid' => $_POST['template'],
    ));
    $c->sortby('modTemplateVar.rank');

    $tvs = $modx->getCollection('modTemplateVar',$c);

    foreach ($tvs as $tv) {
        $tmplvar = '';
        if ($tv->get('type') == 'url') {
            $tmplvar = $_POST['tv'.$tv->get('id')];
            if ($_POST['tv' . $row['name'] . '_prefix'] != '--') {
                $tmplvar = str_replace(array('ftp://','http://'),'', $tmplvar);
                $tmplvar = $_POST['tv'.$tv->get('id').'_prefix'].$tmplvar;
            }
        } elseif ($tv->get('type') == 'file') {
            $tmplvar = $_POST['tv'.$tv->get('id')];
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
        /* save value if it was modified */
        if (strlen($tmplvar) > 0 && $tmplvar != $tv->get('default_text')) {
            $tvr = $modx->newObject('modTemplateVarResource');
            $tvr->set('tmplvarid',$tv->get('id'));
            $tvr->set('value',$tmplvar);
            $tmplvars[] = $tvr;
        }
    }
    $resource->addMany($tmplvars);
} else {
    $_POST['template'] = 0;
}

/* invoke OnBeforeDocFormSave event */
$modx->invokeEvent('OnBeforeDocFormSave',array(
    'mode' => 'new',
    'id' => 0,
));

/* deny publishing if not permitted */
if (!$modx->hasPermission('publish_document')) {
    $_POST['pub_date'] = 0;
    $_POST['unpub_date'] = 0;
    $_POST['published'] = 0;
}

if (isset($_POST['published'])) {
    if (empty($_POST['publishedon'])) {
        $_POST['publishedon'] = $_POST['published'] ? time() : 0;
    } else {
        $_POST['publishedon'] = strtotime($_POST['publishedon']);
    }
    $_POST['publishedby'] = $_POST['published'] ? $modx->user->get('id') : 0;
}

/* set fields */
$resource->fromArray($_POST);

/* increase menu index if this is a new resource */
$auto_menuindex = $modx->getOption('auto_menuindex');
if (!empty($auto_menuindex)) {
    $menuindex = $modx->getCount('modResource',array('parent' => $resource->get('parent')));
}
$resource->set('menuindex',isset($menuindex) ? $menuindex : 0);

/* handle class key */
if (!$resource->get('class_key')) {
    $resource->set('class_key', $resourceClass);
}

/* save data */
if ($resource->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_err_save'));
}

$resource->addLock();

if (is_array($_POST['docgroups'])) {
    foreach ($_POST['docgroups'] as $dgkey => $value) {
        $dgd = $modx->newObject('modResourceGroupResource');
        $dgd->set('document_group',$value);
        $dgd->set('document',$resource->get('id'));
        $dgd->save();
    }
}

if (!empty($_POST['parent'])) {
    $parent = $modx->getObject('modResource', $_POST['parent']);
    $parent->set('isfolder', 1);
    $parent->save();
}

/* invoke OnDocFormSave event */
$modx->invokeEvent('OnDocFormSave',array(
    'mode' => 'new',
    'id' => $resource->get('id'),
    'resource' => & $resource
));

if (!empty($_POST['syncsite']) || !empty($_POST['clearCache'])) {
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

if (!isset($_POST['modx-ab-stay']) || $_POST['modx-ab-stay'] !== 'stay') {
    $resource->removeLock();
}

return $modx->error->success('', array('id' => $resource->get('id')));
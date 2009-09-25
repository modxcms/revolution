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
global $resource;
$modx->lexicon->load('resource');

if (!$modx->hasPermission('new_document')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* load delegate processor */
$resourceClass = !empty($_POST['class_key']) ? $_POST['class_key'] : 'modDocument';
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateProcessor= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateProcessor)) {
    $overridden= include ($delegateProcessor);
    return $overridden;
}

$resource = $modx->newObject($resourceClass);

/* default settings */
$_POST['context_key']= empty($_POST['context_key']) ? 'web' : $_POST['context_key'];
$_POST['parent'] = empty($_POST['parent']) ? 0 : intval($_POST['parent']);
$_POST['template'] = empty($_POST['template']) ? 0 : intval($_POST['template']);
$_POST['hidemenu'] = empty($_POST['hidemenu']) ? 0 : 1;
$_POST['isfolder'] = empty($_POST['isfolder']) ? 0 : 1;
$_POST['richtext'] = empty($_POST['richtext']) ? 0 : 1;
$_POST['donthit'] = empty($_POST['donthit']) ? 0 : 1;
$_POST['published'] = empty($_POST['published']) ? 0 : 1;
$_POST['cacheable'] = empty($_POST['cacheable']) ? 0 : 1;
$_POST['searchable'] = empty($_POST['searchable']) ? 0 : 1;
$_POST['syncsite'] = empty($_POST['syncsite']) ? 0 : 1;
$_POST['createdon'] = strftime('%Y-%m-%d %H:%M:%S');
$_POST['menuindex'] = empty($_POST['menuindex']) ? 0 : $_POST['menuindex'];

/* specific data escaping */
$_POST['pagetitle'] = trim($_POST['pagetitle']);
$_POST['variablesmodified'] = isset($_POST['variablesmodified'])
	? explode(',',$_POST['variablesmodified'])
	: array();
if (isset($_POST['ta'])) $_POST['content'] = $_POST['ta'];

/* default pagetitle */
if (empty($_POST['pagetitle'])) $_POST['pagetitle'] = $modx->lexicon('resource_untitled');

/* friendly url alias checks */
if ($modx->getOption('friendly_alias_urls')) {
    /* auto assign alias */
    if (empty($_POST['alias']) && $modx->getOption('automatic_alias')) {
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
        $_POST['pub_date'] = strtotime($_POST['pub_date']);
        if ($_POST['pub_date'] < $now) $_POST['published'] = 1;
        if ($_POST['pub_date'] > $now) $_POST['published'] = 0;
    }
}

if (isset($_POST['unpub_date'])) {
    if (empty($_POST['unpub_date'])) {
        $_POST['unpub_date'] = 0;
    } else {
        $_POST['unpub_date'] = strtotime($_POST['unpub_date']);
        if ($_POST['unpub_date'] < $now) $_POST['published'] = 0;
    }
}


if (!empty($_POST['template']) && ($template = $modx->getObject('modTemplate', $_POST['template']))) {
    $tmplvars = array();
    $c = $modx->newQuery('modTemplateVar');
    $c->select('DISTINCT modTemplateVar.*, modTemplateVar.default_text AS value');
    $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplates');
    $c->where(array(
        'TemplateVarTemplates.templateid' => $_POST['template'],
    ));
    $c->sortby('modTemplateVar.rank');

    $tvs = $modx->getCollection('modTemplateVar',$c);

    foreach ($tvs as $tv) {
        $value = isset($_POST['tv'.$tv->get('id')]) ? $_POST['tv'.$tv->get('id')] : $tv->get('default_text');

        switch ($tv->get('type')) {
            case 'url':
                if ($_POST['tv' . $row['name'] . '_prefix'] != '--') {
                    $value = str_replace(array('ftp://','http://'),'', $value);
                    $value = $_POST['tv'.$tv->get('id').'_prefix'].$value;
                }
                break;
            default:
                /* handles checkboxes & multiple selects elements */
                if (is_array($value)) {
                    $featureInsert = array();
                    while (list($featureValue, $featureItem) = each($value)) {
                        $featureInsert[count($featureInsert)] = $featureItem;
                    }
                    $tmplvar = implode('||',$featureInsert);
                }
                break;
        }

        /* save value if it was modified */
        if ($value != $tv->get('default_text')) {
            $tvr = $modx->newObject('modTemplateVarResource');
            $tvr->set('tmplvarid',$tv->get('id'));
            $tvr->set('value',$value);
            $tmplvars[] = $tvr;
        }
    }
    $resource->addMany($tmplvars);
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

/* fill out fields */
$resource->fromArray($_POST);
if (!$resource->get('class_key')) {
    $resource->set('class_key', $resourceClass);
}

/* increase menu index if this is a new resource */
$auto_menuindex = $modx->getOption('auto_menuindex');
if (!empty($auto_menuindex)) {
    $menuindex = $modx->getCount('modResource',array('parent' => $resource->get('parent')));
}
$resource->set('menuindex',!empty($menuindex) ? $menuindex : 0);

/* save data */
if ($resource->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_err_save'));
}

/* add lock */
$resource->addLock();

/* save resource groups */
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

if ($resource->get('parent') != 0) {
	$parent = $modx->getObject('modResource', $resource->get('parent'));
	$parent->set('isfolder', 1);
    $parent->save();
}

/* invoke OnDocFormSave event */
$modx->invokeEvent('OnDocFormSave', array(
	'mode' => 'new',
	'id' => $resource->get('id'),
    'resource' => & $resource
));

/* log manager action */
$modx->logManagerAction('save_resource','modDocument',$resource->get('id'));

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

/* quick check to make sure it's not site_start, if so, publish */
if ($resource->get('id') == $modx->getOption('site_start')) {
	$resource->set('published',true);
    $resource->save();
}

if (!isset($_POST['modx-ab-stay']) || $_POST['modx-ab-stay'] !== 'stay') {
    $resource->removeLock();
}

return $modx->error->success('', array('id' => $resource->get('id')));
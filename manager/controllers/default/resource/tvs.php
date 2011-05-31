<?php
/**
 * Loads the TV panel for the resource page.
 *
 * Note: This page is not to be accessed directly.
 *
 * @package modx
 * @subpackage manager
 */
$resourceClass = $resource->get('class_key');
$resourceDir= strtolower(substr($resourceClass, 3));
$resourceId = !empty($resource) ? $resource->get('id') : 0;
if ($resource && $resource instanceof modResource) {
    $_GET['wctx'] = $resource->get('context_key');
}

$onResourceTVFormPrerender = $modx->invokeEvent('OnResourceTVFormPrerender',array(
    'resource' => $resourceId,
));
if (is_array($onResourceTVFormPrerender)) {
    $onResourceTVFormPrerender = implode('',$onResourceTVFormPrerender);
}
$modx->smarty->assign('OnResourceTVFormPrerender',$onResourceTVFormPrerender);

$delegateView= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateView)) {
    $overridden= include_once ($delegateView);
    if ($overridden !== false) {
        return;
    }
}

/* get categories */
$c = $modx->newQuery('modCategory');
$c->sortby('category','ASC');
$categories = $modx->getCollection('modCategory',$c);
$emptycat = $modx->newObject('modCategory');
$emptycat->set('category',ucfirst($modx->lexicon('uncategorized')));
$emptycat->id = 0;
$categories[0] = $emptycat;
$tvMap = array();
$templateId = !empty($templateId) ? $templateId : $resource->get('template');

if ($templateId && ($template = $modx->getObject('modTemplate', $templateId))) {
    $resource = $modx->getObject($resourceClass, $resourceId);
    if (empty($resourceId) || empty($resource)) {
        $resource = $modx->newObject($resourceClass);
        $resourceId = 0;
    } else if (!empty($resourceId) && !$resource->checkPolicy('view')) {
        return $modx->error->failure($modx->lexicon('permission_denied'));
    }
    $resource->set('template',$templateId);

    $tvs = array();
    if ($template) {
        $c = $modx->newQuery('modTemplateVar');
        $c->query['distinct'] = 'DISTINCT';
        $c->select($modx->getSelectColumns('modTemplateVar', 'modTemplateVar'));
        $c->select($modx->getSelectColumns('modCategory', 'Category', 'cat_', array('category')));
        $c->select($modx->getSelectColumns('modTemplateVarResource', 'TemplateVarResource', '', array('value')));
        $c->select($modx->getSelectColumns('modTemplateVarTemplate', 'TemplateVarTemplate', '', array('rank')));
        $c->leftJoin('modCategory','Category');
        $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplate',array(
            'TemplateVarTemplate.tmplvarid = modTemplateVar.id',
            'TemplateVarTemplate.templateid' => $templateId,
        ));
        $c->leftJoin('modTemplateVarResource','TemplateVarResource',array(
            'TemplateVarResource.tmplvarid = modTemplateVar.id',
            'TemplateVarResource.contentid' => $resourceId,
        ));
        $c->sortby('cat_category,TemplateVarTemplate.rank,modTemplateVar.rank','ASC');
        $tvs = $modx->getCollection('modTemplateVar',$c);
        
        $modx->smarty->assign('tvcount',count($tvs));
        foreach ($tvs as $tv) {
            $cat = (int)$tv->get('category');
            $default = $tv->processBindings($tv->get('default_text'),$resourceId);
            if (strpos($tv->get('default_text'),'@INHERIT') > -1 && (strcmp($default,$tv->get('value')) == 0 || $tv->get('value') == null)) {
                $tv->set('inherited',true);
            }
            if ($tv->get('value') == null) {
                $v = $tv->get('default_text');
                if ($tv->get('type') == 'checkbox' && $tv->get('value') == '') {
                    $v = '';
                }
                $tv->set('value',$v);
            }
            
            if ($tv->get('type') == 'richtext') {
                if (is_array($replace_richtexteditor))
                    $replace_richtexteditor = array_merge($replace_richtexteditor, array (
                        'tv' . $tv->id
                    ));
                else
                    $replace_richtexteditor = array (
                        'tv' . $tv->id
                    );
            }
            $inputForm = $tv->renderInput($resource->id);
            if (empty($inputForm)) continue;

            $tv->set('formElement',$inputForm);
            if (!is_array($categories[$cat]->tvs)) {
                $categories[$cat]->tvs = array();
                $categories[$cat]->tvCount = 0;
            }

            /* add to tv/category map */
            $tvMap[$tv->id] = $tv->category;

            /* add TV to category array */
            $categories[$cat]->tvs[] = $tv;
            if ($tv->get('type') != 'hidden') {
                $categories[$cat]->tvCount++;
            }
        }
    }
}

$tvCounts = array();
$finalCategories = array();
foreach ($categories as $n => $category) {
    $category->hidden = empty($category->tvCount) ? true : false;
    $ct = count($category->tvs);
    if ($ct > 0) {
        $finalCategories[$category->get('id')] = $category;
        $tvCounts[$n] = $ct;
    }
}
$onResourceTVFormRender = $modx->invokeEvent('OnResourceTVFormRender',array(
    'categories' => &$finalCategories,
    'template' => $templateId,
    'resource' => $resourceId,
    'tvCounts' => &$tvCounts,
));
if (is_array($onResourceTVFormRender)) {
    $onResourceTVFormRender = implode('',$onResourceTVFormRender);
}
$modx->smarty->assign('OnResourceTVFormRender',$onResourceTVFormRender);

$modx->smarty->assign('categories',$finalCategories);
$modx->smarty->assign('tvCounts',$modx->toJSON($tvCounts));
$modx->smarty->assign('tvMap',$modx->toJSON($tvMap));

if (!empty($_REQUEST['showCheckbox'])) {
    $modx->smarty->assign('showCheckbox',1);
}


return $modx->smarty->fetch('resource/sections/tvs.tpl');

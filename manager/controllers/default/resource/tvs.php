<?php
/**
 * Loads the TV panel for the resource page.
 *
 * Note: This page is not to be accessed directly.
 *
 * @package modx
 * @subpackage manager
 */
$resourceClass= isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : 'modDocument';
$resourceDir= strtolower(substr($resourceClass, 3));

$resourceId = isset($_REQUEST['resource']) ? intval($_REQUEST['resource']) : 0;

$delegateView= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateView)) {
    $overridden= include_once ($delegateView);
    if ($overridden !== false) {
        return;
    }
}

$templateId = 0;

/* get categories */
$c = $modx->newQuery('modCategory');
$c->sortby('category','ASC');
$categories = $modx->getCollection('modCategory',$c);

$emptycat = $modx->newObject('modCategory');
$emptycat->set('category','uncategorized');
$emptycat->id = 0;
$categories[] = $emptycat;
if (isset ($_REQUEST['template'])) {
    $templateId = intval($_REQUEST['template']);
}
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
        $c->select('
            DISTINCT `modTemplateVar`.*,
            `TemplateVarResource`.`value` AS `value`
        ');
        $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplate',array(
            '`TemplateVarTemplate`.`tmplvarid` = `modTemplateVar`.`id`',
            '`TemplateVarTemplate`.templateid' => $templateId,
        ));
        $c->leftJoin('modTemplateVarResource','TemplateVarResource',array(
            '`TemplateVarResource`.`tmplvarid` = `modTemplateVar`.`id`',
            '`TemplateVarResource`.contentid' => $resourceId,
        ));
        $c->sortby('`TemplateVarTemplate`.`rank`,`modTemplateVar`.`rank`');
        $tvs = $modx->getCollection('modTemplateVar',$c);
        
        $modx->smarty->assign('tvcount',count($tvs));
        foreach ($tvs as $tv) {
            $default = $tv->processBindings($tv->get('default_text'),$resourceId);
            if (strpos($tv->get('default_text'),'@INHERIT') > -1 && (strcmp($default,$tv->get('value')) == 0 || $tv->get('value') == null)) {
                $tv->set('inherited',true);
            }
            if ($tv->get('value') == null) {
                $tv->set('value',$tv->get('default_text'));
            }
            
            if ($tv->type == 'richtext') {
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
            if (!is_array($categories[$tv->category]->tvs))
                $categories[$tv->category]->tvs = array();
            $categories[$tv->category]->tvs[$tv->id] = $tv;
        }
    }
}

$onResourceTVFormRender = $modx->invokeEvent('OnResourceTVFormRender',array(
    'categories' => &$categories,
    'template' => $templateId,
    'resource' => $resourceId,
));
if (is_array($onResourceTVFormRender)) {
    $onResourceTVFormRender = implode('',$onResourceTVFormRender);
}
$modx->smarty->assign('OnResourceTVFormRender',$onResourceTVFormRender);

$modx->smarty->assign('categories',$categories);

if (!empty($_REQUEST['showCheckbox'])) {
    $modx->smarty->assign('showCheckbox',1);
}

return $modx->smarty->fetch('resource/sections/tvs.tpl');
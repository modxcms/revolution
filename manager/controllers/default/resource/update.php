<?php
/**
 * Loads the update resource page
 *
 * @package modx
 * @subpackage manager.resource
 */
if (!$modx->hasPermission('edit_document')) return $modx->error->failure($modx->lexicon('access_denied'));

if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('resource_err_nf'));
$resource = $modx->getObject('modResource',$_REQUEST['id']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));

if (!$resource->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* get context */
$modx->smarty->assign('_ctx',$resource->get('context_key'));
$context = $modx->getObject('modContext',$resource->get('context_key'));
if (!$context) { return $modx->error->failure($modx->lexicon('context_err_nf')); }
$context->prepare();

/* check for locked status */
$lockedBy = $resource->addLock($modx->user->get('id'));
$canSave = $modx->hasPermission('save_document') ? 1 : 0;
$locked = false;
$lockedText = '';
if (!empty($lockedBy) && $lockedBy !== true) {
    $canSave = false;
    $locked = true;
    $locker = $modx->getObject('modUser',$lockedBy);
    if ($locker) {
        $lockedBy = $locker->get('username');
    }
    $lockedText = $modx->lexicon('resource_locked_by', array('user' => $lockedBy, 'id' => $resource->get('id')));
}

/* handle custom resource types */
$resourceClass= isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : $resource->get('class_key');
$resourceClass = str_replace(array('../','..','/','\\'),'',$resourceClass);
$resourceDir= strtolower(substr($resourceClass, 3));
$delegateView = dirname(__FILE__) . '/' . $resourceDir . '/';
$delegateView = $modx->getOption(strtolower($resourceClass).'_delegate_path',null,$delegateView) . basename(__FILE__);
$delegateView = str_replace(array('{core_path}','{assets_path}','{base_path}'),array(
    $modx->getOption('core_path',null,MODX_CORE_PATH),
    $modx->getOption('assets_path',null,MODX_ASSETS_PATH),
    $modx->getOption('base_path',null,MODX_BASE_PATH),
),$delegateView);
if (file_exists($delegateView) && realpath($delegateView) !== realpath(__FILE__)) {
    $overridden= include($delegateView);
    if ($overridden !== false) {
        return $overridden;
    }
}

/* set template overrides */
if (isset($_REQUEST['template'])) $resource->set('template',$_REQUEST['template']);

/* invoke OnDocFormPrerender event */
$onDocFormPrerender = $modx->invokeEvent('OnDocFormPrerender',array(
    'id' => $resource->get('id'),
    'resource' => &$resource,
    'mode' => modSystemEvent::MODE_UPD,
));
if (is_array($onDocFormPrerender)) {
    $onDocFormPrerender = implode('',$onDocFormPrerender);
}
$modx->smarty->assign('onDocFormPrerender',$onDocFormPrerender);

/* handle default parent */
$parentname = $context->getOption('site_name', '', $modx->_userConfig);
if ($resource->get('parent') != 0) {
    $parent = $modx->getObject('modResource',$resource->get('parent'));
    if ($parent != null) {
        $parentname = $parent->get('pagetitle');
    }
}
$modx->smarty->assign('parent',$resource->get('parent'));
$modx->smarty->assign('parentname',$parentname);

/* invoke OnDocFormRender event */
$onDocFormRender = $modx->invokeEvent('OnDocFormRender',array(
    'id' => $resource->get('id'),
    'resource' => &$resource,
    'mode' => modSystemEvent::MODE_UPD,
));
if (is_array($onDocFormRender)) $onDocFormRender = implode('',$onDocFormRender);
$onDocFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onDocFormRender);
$modx->smarty->assign('onDocFormRender',$onDocFormRender);

/* get url for resource for preview window */
$url = $modx->makeUrl($resource->get('id'),'','','full');

/* assign resource to smarty */
$modx->smarty->assign('resource',$resource);

/* check permissions */
$publish_document = $modx->hasPermission('publish_document');
$access_permissions = $modx->hasPermission('access_permissions');

/* register JS scripts */
$rte = isset($_REQUEST['which_editor']) ? $_REQUEST['which_editor'] : $context->getOption('which_editor', '', $modx->_userConfig);
$modx->smarty->assign('which_editor',$rte);

/*
 *  Initialize RichText Editor
 */
/* Set which RTE */
if ($context->getOption('use_editor', false, $modx->_userConfig) && !empty($rte)) {
    /* invoke OnRichTextEditorRegister event */
    $text_editors = $modx->invokeEvent('OnRichTextEditorRegister');
    $modx->smarty->assign('text_editors',$text_editors);

    $replace_richtexteditor = array('ta');
    $modx->smarty->assign('replace_richtexteditor',$replace_richtexteditor);

    /* invoke OnRichTextEditorInit event */
    $onRichTextEditorInit = $modx->invokeEvent('OnRichTextEditorInit',array(
        'editor' => $rte,
        'elements' => $replace_richtexteditor,
        'id' => $resource->get('id'),
        'resource' => &$resource,
        'mode' => modSystemEvent::MODE_UPD,
    ));
    if (is_array($onRichTextEditorInit)) {
        $onRichTextEditorInit = implode('',$onRichTextEditorInit);
        $modx->smarty->assign('onRichTextEditorInit',$onRichTextEditorInit);
    }
}

/* register FC rules */
$record = $resource->toArray();
$overridden = $this->checkFormCustomizationRules($resource);
$record = array_merge($record,$overridden);

$record['parent_pagetitle'] = $parent ? $parent->get('pagetitle') : '';

$record['published'] = intval($record['published']) == 1 ? true : false;
$record['hidemenu'] = intval($record['hidemenu']) == 1 ? true : false;
$record['isfolder'] = intval($record['isfolder']) == 1 ? true : false;
$record['richtext'] = intval($record['richtext']) == 1 ? true : false;
$record['searchable'] = intval($record['searchable']) == 1 ? true : false;
$record['cacheable'] = intval($record['cacheable']) == 1 ? true : false;
$record['deleted'] = intval($record['deleted']) == 1 ? true : false;
$record['uri_override'] = intval($record['uri_override']) == 1 ? true : false;

/* get TVs */
$templateId = $record['template'];
$tvCounts = array();
$tvOutput = include dirname(__FILE__).'/tvs.php';
if (!empty($tvCounts)) {
    $modx->smarty->assign('tvOutput',$tvOutput);
}

/* register JS */
$managerUrl = $context->getOption('manager_url', MODX_MANAGER_URL, $modx->_userConfig);
$modx->regClientStartupScript($managerUrl.'assets/modext/util/datetime.js');
$modx->regClientStartupScript($managerUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
$modx->regClientStartupScript($managerUrl.'assets/modext/widgets/resource/modx.grid.resource.security.js');
$modx->regClientStartupScript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
$modx->regClientStartupScript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
$modx->regClientStartupScript($managerUrl.'assets/modext/sections/resource/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
MODx.config.publish_document = "'.$publish_document.'";
MODx.onDocFormRender = "'.$onDocFormRender.'";
MODx.ctx = "'.$resource->get('context_key').'";
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-resource-update"
        ,resource: "'.$resource->get('id').'"
        ,record: '.$modx->toJSON($record).'
        ,access_permissions: "'.$access_permissions.'"
        ,publish_document: "'.$publish_document.'"
        ,preview_url: "'.$url.'"
        ,locked: '.($locked ? 1 : 0).'
        ,lockedText: "'.$lockedText.'"
        ,canSave: '.($canSave ? 1 : 0).'
        ,canEdit: "'.($modx->hasPermission('edit_document') ? 1 : 0).'"
        ,canCreate: "'.($modx->hasPermission('new_document') ? 1 : 0).'"
        ,canDelete: "'.($modx->hasPermission('delete_document') ? 1 : 0).'"
        ,show_tvs: '.(!empty($tvCounts) ? 1 : 0).'
    });
});
// ]]>
</script>');

$modx->smarty->assign('_pagetitle',$modx->lexicon('editing',array('name'  => $record['pagetitle'])));
return $modx->smarty->fetch('resource/update.tpl');

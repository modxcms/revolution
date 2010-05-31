<?php
/**
 * @package modx
 * @subpackage controllers.resource.weblink
 */
if (!$modx->hasPermission('edit_document')) return $modx->error->failure($modx->lexicon('access_denied'));

if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('resource_err_nf'));
$resource = $modx->getObject('modResource',$_REQUEST['id']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));

if (!$resource->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_permission_denied'));
}

$lockedBy = $resource->addLock($modx->user->get('id'));
if (!empty($lockedBy) && $lockedBy !== true) {
    if ($user = $modx->getObject('modUser', $lockedBy)) {
        $lockedBy = $user->get('username');
    }
    return $modx->error->failure($modx->lexicon('resource_locked_by', array('user' => $lockedBy, 'id' => $resource->get('id'))));
}

if (isset($_REQUEST['template'])) $resource->set('template',$_REQUEST['template']);

/* invoke OnDocFormPrerender event */
$onDocFormPrerender = $modx->invokeEvent('OnDocFormPrerender',array(
    'id' => $resource->get('id'),
    'resource' => &$resource,
    'mode' => 'upd',
));
if (is_array($onDocFormPrerender)) {
    $onDocFormPrerender = implode('',$onDocFormPrerender);
}
$modx->smarty->assign('onDocFormPrerender',$onDocFormPrerender);

/* handle default parent */
$parentname = $modx->getOption('site_name');
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
    'mode' => 'upd',
));
if (is_array($onDocFormRender)) $onDocFormRender = implode('',$onDocFormRender);
$onDocFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onDocFormRender);
$modx->smarty->assign('onDocFormRender',$onDocFormRender);

/* get url for resource for preview window */
$url = $modx->makeUrl($resource->get('id'));

/* assign weblink to smarty */
$modx->smarty->assign('resource',$resource);

/*
 *  Initialize RichText Editor
 */
/* Set which RTE */
$rte = isset($_REQUEST['which_editor']) ? $_REQUEST['which_editor'] : $modx->getOption('which_editor');
$modx->smarty->assign('which_editor',$rte);
if ($modx->getOption('use_editor') && !empty($rte)) {
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
        'mode' => 'upd',
    ));
    if (is_array($onRichTextEditorInit)) {
        $onRichTextEditorInit = implode('',$onRichTextEditorInit);
        $modx->smarty->assign('onRichTextEditorInit',$onRichTextEditorInit);
    }
}

/* check permissions */
$publish_document = $modx->hasPermission('publish_document');
$access_permissions = $modx->hasPermission('access_permissions');

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/util/datetime.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.tv.renders.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.grid.resource.security.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.tv.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.weblink.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/resource/weblink/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
MODx.config.publish_document = "'.$publish_document.'";
MODx.onDocFormRender = "'.$onDocFormRender.'";
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-weblink-update"
        ,resource: "'.$resource->get('id').'"
        ,template: "'.$resource->get('template').'"
        ,content_type: "'.$resource->get('content_type').'"
        ,class_key: "'.$resource->get('class_key').'"
        ,context_key: "'.$resource->get('context_key').'"
        ,parent: "'.$resource->get('parent').'"
        ,deleted: "'.$resource->get('deleted').'"
        ,published: "'.$resource->get('published').'"
        ,which_editor: "'.$which_editor.'"
        ,access_permissions: "'.$access_permissions.'"
        ,publish_document: "'.$publish_document.'"
        ,preview_url: "'.$url.'"
        ,canSave: "'.($modx->hasPermission('save_document') ? 1 : 0).'"
        ,canEdit: "'.($modx->hasPermission('edit_document') ? 1 : 0).'"
        ,canCreate: "'.($modx->hasPermission('new_document') ? 1 : 0).'"
    });
});
// ]]>
</script>');


return $modx->smarty->fetch('resource/weblink/update.tpl');

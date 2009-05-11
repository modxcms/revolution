<?php
/**
 * Loads the create resource page
 *
 * @package modx
 * @subpackage manager.resource
 */
if (!$modx->hasPermission('new_document')) return $modx->error->failure($modx->lexicon('access_denied'));

$resourceClass= isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : 'modDocument';
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateView= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateView)) {
    $overridden= include_once ($delegateView);
    if ($overridden !== false) {
        return $overridden;
    }
}

$resource = $modx->newObject($resourceClass);

/* handle template inheritance */
if (isset($_REQUEST['parent'])) {
    $parent = $modx->getObject('modResource',$_REQUEST['parent']);
    if ($parent != null) {
        $modx->smarty->assign('parent',$parent);
    }
} else { $parent = null; }

/* handle switch template */
if (isset ($_REQUEST['newtemplate'])) {
	foreach ($_POST as $key => $val) {
		$resource->set($key,$val);
	}
    $resource->set('content',$_POST['ta']);
    $pub_date = $resource->get('pub_date');
    if (!empty($pub_date) && $pub_date != '') {
        list ($d, $m, $Y, $H, $M, $S) = sscanf($pub_date, "%2d-%2d-%4d %2d:%2d:%2d");
        $pub_date = strtotime("$m/$d/$Y $H:$M:$S");
        $resource->set('pub_date',$pub_date);
    }
    $unpub_date = $resource->get('unpub_date');
    if (!empty($unpub_date) && $unpub_date != '') {
        list ($d, $m, $Y, $H, $M, $S) = sscanf($unpub_date, "%2d-%2d-%4d %2d:%2d:%2d");
        $unpub_date = strtotime("$m/$d/$Y $H:$M:$S");
        $resource->set('unpub_date',$unpub_date);
    }
}

/* invoke OnDocFormPrerender event */
$onDocFormPrerender = $modx->invokeEvent('OnDocFormPrerender',array('id' => 0));
if (is_array($onDocFormPrerender)) {
    $onDocFormPrerender = implode('',$onDocFormPrerender);
}
$modx->smarty->assign('onDocFormPrerender',$onDocFormPrerender);

/* handle default parent */
$parentname = $modx->getOption('site_name');
$resource->set('parent',0);
if (isset ($_REQUEST['parent'])) {
	if ($_REQUEST['parent'] == 0) {
		$parentname = $modx->getOption('site_name');
	} else {
		$parent = $modx->getObject('modResource',$_REQUEST['parent']);
		if ($parent != null) {
		  $parentname = $parent->get('pagetitle');
		  $resource->set('parent',$parent->get('id'));
        }
	}
}
$modx->smarty->assign('parentname',$parentname);

/* set permissions on the resource based on the permissions of the parent resource
 * TODO: get working in revo, move to get processor
 */
$groupsarray = array ();
if (!empty ($_REQUEST['parent'])) {
    $dgds = $modx->getCollection('modResourceGroupResource',array('document' => $_REQUEST['parent']));
    foreach ($dgds as $dgd) {
        $groupsarray[$dgd->get('id')] = $dgd->get('document_group');
    }
}
$c = $modx->newQuery('modResourceGroup');
$c->sortby('name','ASC');
$docgroups = $modx->getCollection('modResourceGroup',$c);
foreach ($docgroups as $docgroup) {
    $checked = in_array($docgroup->get('id'),$groupsarray);
    $docgroup->set('selected',$checked);
}
$modx->smarty->assign('docgroups',$docgroups);
$modx->smarty->assign('hasdocgroups',count($docgroups) > 0);


/* invoke OnDocFormRender event */
$onDocFormRender = $modx->invokeEvent('OnDocFormRender',array('id' => 0));
if (is_array($onDocFormRender)) {
    $onDocFormRender = implode('',$onDocFormRender);
}
$modx->smarty->assign('onDocFormRender',$onDocFormRender);


/*
 *  Initialize RichText Editor
 */
/* Set which RTE */
$rte = isset($_REQUEST['which_editor']) ? $_REQUEST['which_editor'] : $modx->getOption('which_editor');
$modx->smarty->assign('which_editor',$rte);
if ($modx->getOption('use_editor')) {
    /* invoke OnRichTextEditorRegister event */
    $text_editors = $modx->invokeEvent('OnRichTextEditorRegister');
    $modx->smarty->assign('text_editors',$text_editors);

    $replace_richtexteditor = array('ta');
    $modx->smarty->assign('replace_richtexteditor',$replace_richtexteditor);

	/* invoke OnRichTextEditorInit event */
	$onRichTextEditorInit = $modx->invokeEvent('OnRichTextEditorInit',array(
		'editor' => $rte,
		'elements' => $replace_richtexteditor,
	));
	if (is_array($onRichTextEditorInit)) {
		$onRichTextEditorInit = implode('',$onRichTextEditorInit);
		$modx->smarty->assign('onRichTextEditorInit',$onRichTextEditorInit);
    }
}

/* assign resource to smarty */
$modx->smarty->assign('resource',$resource);

/* check permissions */
$publish_document = $modx->hasPermission('publish_document');
$edit_doc_metatags = $modx->hasPermission('edit_doc_metatags');
$access_permissions = $modx->hasPermission('access_permissions');

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/core/modx.view.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/util/datetime.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.browser.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.tree.directory.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.tv.renders.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.grid.resource.security.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.tv.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/resource/create.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
MODx.config.publish_document = "'.$publish_document.'";
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-resource-create"
        ,template: "'.($parent != null ? $parent->get('template') : $modx->getOption('default_template')).'"
        ,content_type: "1"
        ,class_key: "'.(isset($_REQUEST['class_key']) ? $_REQUEST['class_key'] : 'modDocument').'"
        ,context_key: "'.(isset($_REQUEST['context_key']) ? $_REQUEST['context_key'] : 'web').'"
        ,parent: "'.(isset($_REQUEST['parent']) ? $_REQUEST['parent'] : '0').'"
        ,which_editor: "'.$which_editor.'"
        ,edit_doc_metatags: "'.$edit_doc_metatags.'"
        ,access_permissions: "'.$access_permissions.'"
        ,publish_document: "'.$publish_document.'"
    });
});
// ]]>
</script>');

return $modx->smarty->fetch('resource/create.tpl');

<?php
/**
 * @package modx
 * @subpackage controllers.resource.weblink
 */
if (!$modx->hasPermission('edit_document')) return $modx->error->failure($modx->lexicon('access_denied'));

$resource = $modx->getObject('modResource',$_REQUEST['id']);
if ($resource == null) return $modx->error->failure(sprintf($modx->lexicon('resource_with_id_not_found'), $_REQUEST['id']));

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
$onDocFormPrerender = $modx->invokeEvent('OnDocFormPrerender',array('id' => 0));
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

$groupsarray = array ();
$dgds = $modx->getCollection('modResourceGroupResource',array('document' => $resource->get('id')));
foreach ($dgds as $dgd) {
    $groupsarray[$dgd->get('id')] = $dgd->get('document_group');
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

/* assign weblink to smarty */
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
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.weblink.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/resource/weblink/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
MODx.config.publish_document = "'.$publish_document.'";
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-weblink-update"
        ,id: "'.$resource->get('id').'"
        ,template: "'.$resource->get('template').'"
        ,content_type: "'.$resource->get('content_type').'"
        ,class_key: "'.$resource->get('class_key').'"
        ,context_key: "'.$resource->get('context_key').'"
        ,parent: "'.$resource->get('parent').'"
        ,deleted: "'.$resource->get('deleted').'"
        ,published: "'.$resource->get('published').'"
        ,which_editor: "'.$which_editor.'"
        ,edit_doc_metatags: "'.$edit_doc_metatags.'"
        ,access_permissions: "'.$access_permissions.'"
        ,publish_document: "'.$publish_document.'"
    });
});
// ]]>
</script>');


return $modx->smarty->fetch('resource/weblink/update.tpl');

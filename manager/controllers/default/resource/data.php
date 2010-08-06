<?php
/**
 * Loads the resource data page
 *
 * @package modx
 * @subpackage manager.resource
 */
if (!$modx->hasPermission('view_document')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

$resource = $modx->getObject('modResource', $_REQUEST['id']);
if ($resource == null) return $modx->error->failure(sprintf($modx->lexicon('resource_with_id_not_found'), $_REQUEST['id']));

if (!$resource->checkPolicy('view')) return $modx->error->failure($modx->lexicon('access_denied'));

$resourceClass= isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : $resource->get('class_key');
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateView= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateView)) {
    $overridden= include_once ($delegateView);
    if ($overridden !== false) {
        return;
    }
}

$resource->getOne('CreatedBy');
$resource->getOne('EditedBy');
$resource->getOne('Template');

$dkws = $resource->getMany('ResourceKeywords');
$resource->keywords = array();
foreach ($dkws as $dkw) {
	$resource->keywords[$dkw->get('keyword_id')] = $dkw->getOne('Keyword');
}
$keywords = array();
foreach ($resource->keywords as $kw) {
	$keywords[] = $kw->get('keyword');
}
$keywords = join($keywords,',');
$modx->smarty->assign('keywords',$keywords);

$server_offset_time= intval($modx->getOption('server_offset_time',null,0));
$resource->set('createdon_adjusted',strftime('%c', $resource->get('createdon') + $server_offset_time));
$resource->set('editedon_adjusted',strftime('%c', $resource->get('editedon') + $server_offset_time));

$buffer = '';
$resource->_contextKey= $resource->get('context_key');
if ($buffer = $modx->cacheManager->get($resource->getCacheKey())) {
    $modx->smarty->assign('buffer', htmlspecialchars($buffer['resource']['_content']));
}
/* assign resource to smarty */
/* assign resource to smarty */
$modx->smarty->assign('resource',$resource);

/* register JS scripts */
$modx->smarty->assign('_ctx',$resource->get('context_key'));
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.data.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/resource/data.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.ctx = "'.$resource->get('context_key').'";
    MODx.load({
        xtype: "modx-page-resource-data"
        ,id: "'.$resource->get('id').'"
        ,context_key: "'.$resource->get('context_key').'"
        ,class_key: "'.$resource->get('class_key').'"
        ,pagetitle: "'.$resource->get('pagetitle').'"
        ,canEdit: "'.($modx->hasPermission('edit_document') ? 1 : 0).'"
    });
});
// ]]>
</script>');

return $modx->smarty->fetch('resource/data.tpl');